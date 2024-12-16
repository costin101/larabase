<?php namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    public static function bootCacheable()
    {
        static::saved(function ($model) {
            $model->cache(); // Cache the model automatically on save
        });

        static::deleted(function ($model) {
            $model->clearCache(); // Clear cache automatically on delete
        });
    }

    public static function find($id, $columns = ['*'])
    {
        $instance = new static();
        $key = $instance->getCacheKey($id);

        return Cache::remember($key, now()->addYear(), function () use ($id, $columns) {
            return parent::find($id, $columns);
        });
    }

    public function cache()
    {
        $key = $this->getCacheKey();
        Cache::put($key, $this, now()->addYear());
    }

    public function clearCache()
    {
        Cache::forget($this->getCacheKey());
    }

    public function getCacheKey($id = null)
    {
        $id = $id ?? $this->id;
        return sprintf('%s.%s', strtolower(class_basename($this)), $id);
    }
}
