<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }


  protected static function booted()
{
    static::creating(function ($category) {
        if (backpack_auth()->check()) {
            $category->created_by = backpack_auth()->id();
            
        }
    });

    static::updating(function ($category) {
        if (backpack_auth()->check()) {
            $category->updated_by = backpack_auth()->id();
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
}
