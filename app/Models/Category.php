<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'name',
        'slug'
    ];

    /**
     * Generación automática del slug al crear/actualizar
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    /**
     * Relación con Productos (una categoría tiene muchos productos)
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}