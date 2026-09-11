<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUppercaseAttributes
{
    /**
     * Exenciones por defecto de campos que no deben convertirse a minúsculas.
     */
    protected static array $defaultExemptions = [
        'email',
        'password',
        'remember_token',
        'attachments',
        'image',
        'photo',
        'url',
        'url_path',
        'icon',
        'code',
        'computer_code',
        'asset_code',
        'ticket_code',
        'serial_number',
        'document_number',
        'type',
    ];

    public static function bootHasUppercaseAttributes(): void
    {
        static::saving(function ($model) {
            $exemptions = array_merge(
                self::$defaultExemptions,
                property_exists($model, 'lowercaseExemptions') ? $model->lowercaseExemptions : [],
                property_exists($model, 'uppercaseExemptions') ? $model->uppercaseExemptions : []
            );

            foreach ($model->getAttributes() as $key => $value) {
                if (is_string($value) && ! in_array($key, $exemptions, true)) {
                    // Evitar transformar JSON, URLs, Hashes de Password o Emails
                    if (
                        ! Str::startsWith($value, ['$2y$', 'http://', 'https://', '{', '['])
                        && ! filter_var($value, FILTER_VALIDATE_EMAIL)
                    ) {
                        $model->attributes[$key] = mb_strtolower($value, 'UTF-8');
                    }
                }
            }
        });
    }
}