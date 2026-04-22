<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'company_website',
        'company_logo',
        'company_favicon',
        'tax_number',
        'commercial_register',
        'invoice_terms',
        'currency',
        'timezone',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the global settings instance
     */
    public static function getSettings()
    {
        return cache()->remember('app_settings', 3600, function () {
            return self::first() ?? self::create([
                'company_name' => 'شركة بهجة للمنظفات',
                'currency' => 'ج.م',
                'timezone' => 'Africa/Cairo',
                'is_active' => true,
            ]);
        });
    }

    /**
     * Update settings and clear cache
     */
    public static function updateSettings(array $data)
    {
        cache()->forget('app_settings');
        $settings = self::first();
        if ($settings) {
            $settings->update($data);
        } else {
            self::create($data);
        }
        return self::getSettings();
    }

    /**
     * Get company logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->company_logo && file_exists(storage_path('app/public/' . $this->company_logo))) {
            return asset('storage/' . $this->company_logo);
        }
        return null;
    }

    /**
     * Get company favicon URL
     */
    public function getFaviconUrlAttribute()
    {
        if ($this->company_favicon && file_exists(storage_path('app/public/' . $this->company_favicon))) {
            return asset('storage/' . $this->company_favicon);
        }
        return null;
    }
}
