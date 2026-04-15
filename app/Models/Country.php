<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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

    public static function getAllCached(): \Illuminate\Support\Collection
    {
        Cache::rememberForever('countries:all', function () {
            return self::all();
        });

        return Cache::get('countries:all');
    }

    public function clearCache(): void
    {
        Cache::forget('countries:all');
    }
}