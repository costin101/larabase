<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory, Cacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'iso_alpha2',
        'iso_alpha3',
        'phone_prefix',
        'minimum_age',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'minimum_age' => -1,
    ];

    public function cacheableTtl(): int
    {
        return -1; // Cache indefinitely
    }
}