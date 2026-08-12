<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'username',
    'name',
    'email',
    'password',
    'document_type_id',
    'document_number',
    'labor_condition_id',
    'office_id',
    'personal_id',
    'is_active',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->username)) {
                $user->username = $user->generateUniqueUsername();
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Genera un username único a partir del nombre completo.
     * Ejemplo: "Juan Perez Prado" -> "jperez"
     */
    public function generateUniqueUsername(): string
    {
        $cleanedName = preg_replace('/\s+/', ' ', trim($this->name));
        $parts = explode(' ', $cleanedName);

        if (count($parts) === 0 || empty($parts[0])) {
            return 'user_'.Str::random(5);
        }

        $firstName = mb_strtolower($parts[0]);
        $lastName = count($parts) > 1 ? mb_strtolower($parts[1]) : '';

        // Limpiar acentos y caracteres especiales
        $firstNameClean = Str::ascii($firstName);
        $lastNameClean = Str::ascii($lastName);

        $firstLetter = mb_substr($firstNameClean, 0, 1);
        $baseUsername = $firstLetter.$lastNameClean;

        // Remover caracteres no alfanuméricos
        $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);

        if (empty($baseUsername)) {
            $baseUsername = 'user';
        }

        $username = $baseUsername;
        $count = 1;
        while (self::where('username', $username)->exists()) {
            $username = $baseUsername.(++$count);
        }

        return $username;
    }

    /**
     * @return BelongsTo<DocumentType, $this>
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * @return BelongsTo<LaborCondition, $this>
     */
    public function laborCondition(): BelongsTo
    {
        return $this->belongsTo(LaborCondition::class);
    }

    /**
     * @return BelongsTo<Office, $this>
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * @return MorphToMany<Role, $this>
     */
    public function allRoles(): MorphToMany
    {
        return $this->morphToMany(Role::class, 'model', 'model_has_roles', 'model_id', 'role_id')
            ->withPivot('subsystem_id');
    }

    /**
     * Indica si el usuario pertenece al personal técnico / TI facultado para evaluaciones y bajas.
     */
    public function isTiStaff(): bool
    {
        $userRoles = $this->allRoles->pluck('name')->toArray();
        $techRoles = ['Administrador Central', 'Administrador de TI', 'Técnico de Soporte'];

        return count(array_intersect($userRoles, $techRoles)) > 0;
    }
}

