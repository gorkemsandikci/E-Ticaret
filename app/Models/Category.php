<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
    use Sluggable;
    protected $fillable = [
        'name',
        'slug',
        'content',
        'image',
        'thumbnail',
        'cat_ust'
    ];

    public function items()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
