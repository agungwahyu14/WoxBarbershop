<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'duration', 'is_active', 'name_id', 'name_en', 'description_id', 'description_en'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get name attribute based on current locale.
     */
    public function getNameAttribute($value)
    {
        $locale = app()->getLocale();
        
        // Prioritize Indonesian locale
        if ($locale === 'id' && !empty($this->name_id)) {
            return $this->name_id;
        }
        
        // Fallback to English if Indonesian is empty
        if ($locale === 'id' && !empty($this->name_en)) {
            return $this->name_en;
        }
        
        // For English locale, prioritize English
        if ($locale === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        
        // Fallback to Indonesian if English is empty
        if ($locale === 'en' && !empty($this->name_id)) {
            return $this->name_id;
        }
        
        // Final fallback to default name field
        return $value;
    }

    /**
     * Get description attribute based on current locale.
     */
    public function getDescriptionAttribute($value)
    {
        $locale = app()->getLocale();
        
        // Prioritize Indonesian locale
        if ($locale === 'id' && !empty($this->description_id)) {
            return $this->description_id;
        }
        
        // Fallback to English if Indonesian is empty
        if ($locale === 'id' && !empty($this->description_en)) {
            return $this->description_en;
        }
        
        // For English locale, prioritize English
        if ($locale === 'en' && !empty($this->description_en)) {
            return $this->description_en;
        }
        
        // Fallback to Indonesian if English is empty
        if ($locale === 'en' && !empty($this->description_id)) {
            return $this->description_id;
        }
        
        // Final fallback to default description field
        return $value;
    }

    /**
     * Get name by specific locale.
     */
    public function getNameByLocale($locale)
    {
        switch ($locale) {
            case 'id':
                return $this->name_id ?: ($this->name_en ?: $this->name);
            case 'en':
                return $this->name_en ?: ($this->name_id ?: $this->name);
            default:
                return $this->name;
        }
    }

    /**
     * Get description by specific locale.
     */
    public function getDescriptionByLocale($locale)
    {
        switch ($locale) {
            case 'id':
                return $this->description_id ?: ($this->description_en ?: $this->description);
            case 'en':
                return $this->description_en ?: ($this->description_id ?: $this->description);
            default:
                return $this->description;
        }
    }

    /**
     * Get name_id attribute.
     */
    public function getNameIdAttribute()
    {
        return $this->attributes['name_id'] ?? null;
    }

    /**
     * Get name_en attribute.
     */
    public function getNameEnAttribute()
    {
        return $this->attributes['name_en'] ?? null;
    }

    /**
     * Get description_id attribute.
     */
    public function getDescriptionIdAttribute()
    {
        return $this->attributes['description_id'] ?? null;
    }

    /**
     * Get description_en attribute.
     */
    public function getDescriptionEnAttribute()
    {
        return $this->attributes['description_en'] ?? null;
    }
}
