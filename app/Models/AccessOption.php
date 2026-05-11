<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessOption extends Model
{
    protected $fillable = [
        'category',
        'name',
        'key',
        'has_sub_options',
        'sub_options',
        'sub_option_type',
        'custom_fields',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sub_options' => 'array',
        'custom_fields' => 'array',
        'has_sub_options' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getSubOptionsStringAttribute()
    {
        return $this->sub_options ? implode(', ', $this->sub_options) : '';
    }

    public function scopeSystem($query)
    {
        return $query->where('category', 'system');
    }

    public function scopeProgram($query)
    {
        return $query->where('category', 'program');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
