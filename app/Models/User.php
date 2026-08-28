<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUppercaseAttributes;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
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
class User extends Authenticatable implements FilamentUser
{
    use HasUppercaseAttributes;

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
    /**
     * Genera un username único a partir del nombre completo.
     * Regla: Primera letra del primer nombre + primer apellido.
     * Si existe coincidencia con otro usuario, toma la primera letra del segundo apellido.
     * Ejemplo 1: "Juan Pérez Prado" -> "jperez"
     * Ejemplo 2 (Si "jperez" ya existe): "José Pérez Prado" -> "jperezp"
     */

    /**
     * Nombre visible oficial del usuario (toma el nombre completo de RRHH si está vinculado).
     */
    public function getNameAttribute(?string $value): string
    {
        return $this->personal?->full_name ?? $value ?? $this->username ?? '';
    }

    /**
     * ID de Oficina asignada (delegada desde RRHH si está vinculado).
     */
    public function getOfficeIdAttribute(?int $value): ?int
    {
        return $this->personal?->office_id ?? $value;
    }

    /**
     * Número de Documento (delegado desde RRHH si está vinculado).
     */
    public function getDocumentNumberAttribute(?string $value): ?string
    {
        return $this->personal?->document_number ?? $value;
    }

    /**
     * Condición Laboral (delegada desde RRHH si está vinculado).
     */
    public function getLaborConditionIdAttribute(?int $value): ?int
    {
        return $this->personal?->labor_condition_id ?? $value;
    }

    public function generateUniqueUsername(): string
    {
        $targetName = $this->personal?->full_name ?? $this->name ?? '';
        $cleanedName = preg_replace('/\s+/', ' ', trim($targetName));
        $parts = array_values(array_filter(explode(' ', $cleanedName)));

        if (count($parts) === 0) {
            return 'user_' . Str::random(5);
        }

        $firstName = Str::ascii(mb_strtolower($parts[0]));
        $firstLetterName = mb_substr($firstName, 0, 1);

        $firstSurname = '';
        $secondSurname = '';

        if (count($parts) >= 3) {
            $firstSurname = Str::ascii(mb_strtolower($parts[count($parts) - 2]));
            $secondSurname = Str::ascii(mb_strtolower($parts[count($parts) - 1]));
        } elseif (count($parts) === 2) {
            $firstSurname = Str::ascii(mb_strtolower($parts[1]));
        } else {
            $firstSurname = $firstName;
        }

        $firstSurnameClean = preg_replace('/[^a-z0-9]/', '', $firstSurname);
        $secondSurnameClean = preg_replace('/[^a-z0-9]/', '', $secondSurname);
        $firstLetterNameClean = preg_replace('/[^a-z0-9]/', '', $firstLetterName);

        // Candidato 1: Primera letra del primer nombre + primer apellido
        $candidate1 = $firstLetterNameClean . $firstSurnameClean;

        if (! self::where('username', $candidate1)->exists()) {
            return $candidate1;
        }

        // Candidato 2: Si hay coincidencia con otro usuario, tomar la primera letra del segundo apellido
        if (! empty($secondSurnameClean)) {
            $firstLetterSecondSurname = mb_substr($secondSurnameClean, 0, 1);
            $candidate2 = $candidate1 . $firstLetterSecondSurname;

            if (! self::where('username', $candidate2)->exists()) {
                return $candidate2;
            }
        }

        // Fallback en caso extremo de colisión múltiple: agregador numérico
        $counter = 2;
        while (self::where('username', $candidate1 . $counter)->exists()) {
            $counter++;
        }

        return $candidate1 . $counter;
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
     * @return BelongsTo<Personal, $this>
     */
    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'personal_id');
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

    /**
     * Permite autenticarse a nivel de sistema. La autorización de subsistemas se gestiona centralizadamente.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_active;
    }
}
