<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_id',
        'name_en',
        'description',
        'description_id',
        'description_en',
        'price',
        // 'category',
        'image',
        'stock',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    // Accessor untuk image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return asset('images/product-placeholder.png');
    }

    // Accessor untuk name berdasarkan locale
    public function getNameAttribute($value)
    {
        $locale = App::getLocale();
        
        if ($locale === 'id' && !empty($this->name_id)) {
            return $this->name_id;
        } elseif ($locale === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        
        return $value;
    }

    // Accessor untuk description berdasarkan locale
    public function getDescriptionAttribute($value)
    {
        $locale = App::getLocale();
        
        if ($locale === 'id' && !empty($this->description_id)) {
            return $this->description_id;
        } elseif ($locale === 'en' && !empty($this->description_en)) {
            return $this->description_en;
        }
        
        return $value;
    }

    // Method untuk mendapatkan name berdasarkan bahasa spesifik
    public function getNameByLocale($locale)
    {
        switch ($locale) {
            case 'id':
                return $this->name_id ?: $this->name;
            case 'en':
                return $this->name_en ?: $this->name;
            default:
                return $this->name;
        }
    }

    // Method untuk mendapatkan description berdasarkan bahasa spesifik
    public function getDescriptionByLocale($locale)
    {
        switch ($locale) {
            case 'id':
                return $this->description_id ?: $this->description;
            case 'en':
                return $this->description_en ?: $this->description;
            default:
                return $this->description;
        }
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
