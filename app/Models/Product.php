<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use CrudTrait;
     use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'images',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
    ];

    /**
     * Get the category that owns the product.
     */
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

    static::deleting(function ($category) {
        if (backpack_auth()->check()) {
            $category->deleted_by = backpack_auth()->id();
        }
    });
}

    /**
     * Get the user who created the product.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the product.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function setImagesAttribute($value)
    {
        $attribute_name = "images";
        $disk = "public";
        $destination_path = "uploads/products";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
