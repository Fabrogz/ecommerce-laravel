<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'is_featured',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'status' => 'boolean'
    ];

    protected $appends = ['formatted_price', 'image_url'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = $product->generateUniqueSlug($product->name);
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = $product->generateUniqueSlug($product->name, $product->id);
            }
        });
    }

    protected function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $counter = 1;
        
        $query = static::where('slug', $slug);
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        while ($query->exists()) {
            $slug = Str::slug($name).'-'.$counter;
            $counter++;
        }
        
        return $slug;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$'.number_format($this->price, 2);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : asset('images/default-product.png');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}