<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\HasUploadFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use CrudTrait, HasFactory, HasUploadFields;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'images',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted()
    {
        static::creating(function ($product) {
            if (backpack_auth()->check()) {
                $product->created_by = backpack_auth()->id();
            }
        });

        static::updating(function ($product) {
            if (backpack_auth()->check()) {
                $product->updated_by = backpack_auth()->id();
            }
        });

        static::deleting(function ($product) { // <-- FIXED HERE
            if (backpack_auth()->check()) {
                $product->deleted_by = backpack_auth()->id();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function setImagesAttribute($value)
    {
        $this->uploadMultipleFilesToDisk($value, 'images', 'public', 'products');
    }

    public function getImagesAttribute($value)
    {
        $images = json_decode($value, true) ?? [];

        return array_map(function ($item) {
            return is_array($item) ? ($item['path'] ?? '') : $item;
        }, $images);
    }
}
