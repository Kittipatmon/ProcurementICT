<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get all procurement requests in this category.
     */
    public function procurementRequests()
    {
        return $this->hasMany(ProcurementRequest::class, 'category', 'slug');
    }
}
