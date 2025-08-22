<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable= ['image', 'name', 'slug'];
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category){
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
