<?php namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait Cacheable
{
    /**
     * Stores each attribute as a separate Redis key plus a sentinel.
     */
    public function cacheableRelations(): array
    {
        return [];
    }

    /**
     * Define which fields to cache. Override in model for explicit control,
     * otherwise falls back to fillable + primary key.
     */
    public function cacheableFields(): array
    {
        $fillable = $this->getFillable();
        $pk = $this->getKeyName();

        return array_unique(array_merge([$pk], $fillable));
    }

    public static function bootCacheable(): void
    {
        static::saved(function ($model) {
            if ($model->wasRecentlyCreated) {
                $model->cache();
            } else {
                $model->cacheDirty();
            }
        });

        static::deleted(function ($model) {
            $model->clearCache();
        });
    }

    public static function findCacheable(int|string $id): ?static
    {
        $instance = new static();
        $prefix   = $instance->getCachePrefix($id);
        $fields   = $instance->cacheableFields();

        if (empty($fields)) {
            return static::query()->find($id);
        }

        // Use a sentinel key to distinguish "not cached" from "cached with nulls"
        $sentinelKey = "{$prefix}:__cached__";
        $fieldKeys   = array_map(fn($f) => "{$prefix}:{$f}", $fields);

        $results  = Redis::mget(array_merge([$sentinelKey], $fieldKeys));
        $sentinel = array_shift($results); // first result is the sentinel

        if ($sentinel === null) {
            // Cache miss — load from DB and populate cache
            $model = static::query()->find($id);
            $model?->cache();
            return $model;
        }

        $attributes = array_combine($fields, $results);

        // Redis returns strings; restore actual nulls (stored as the literal "NULL")
        $attributes = array_map(
            fn($v) => $v === '__NULL__' ? null : $v,
            $attributes
        );

        return $instance->newFromBuilder($attributes);
    }

    public function cache(): void
    {
        $prefix = $this->getCachePrefix();
        $ttl    = $this->cacheableTtl();
        $fields = $this->cacheableFields();

        if ($ttl < 0) {
            // Negative TTL means "cache indefinitely" — use set instead of setex
            Redis::set("{$prefix}:__cached__", '1');
            foreach ($fields as $field) {
                $value = $this->{$field};
                $stored = $value === null ? '__NULL__' : (string) $value;
                Redis::set("{$prefix}:{$field}", $stored);
            }
        } else {
            // Use a pipeline to minimize round-trips
            Redis::pipeline(function ($pipe) use ($prefix, $fields, $ttl) {
                $pipe->setex("{$prefix}:__cached__", $ttl, '1');
                foreach ($fields as $field) {
                    $value = $this->{$field};
                    $stored = $value === null ? '__NULL__' : (string) $value;
                    $pipe->setex("{$prefix}:{$field}", $ttl, $stored);
                }
            });
        }

        $this->cacheRelations($ttl);
    }


    /**
     * Syncs only changed attributes to Redis (refreshes TTL on touched keys).
     */
    public function cacheDirty(): void
    {
        $dirty = $this->getDirty();
        if (empty($dirty)) {
            return;
        }

        $prefix = $this->getCachePrefix();
        $ttl    = $this->cacheableTtl();

        if ($ttl < 0) {
            // Negative TTL means "cache indefinitely"
            Redis::pipeline(function ($pipe) use ($prefix, $dirty, $ttl) {
                foreach ($dirty as $field => $value) {
                    $stored = $value === null ? '__NULL__' : (string) $value;
                    $pipe->setex("{$prefix}:{$field}", $stored);
                }
            });
        } else {
            Redis::pipeline(function ($pipe) use ($prefix, $dirty, $ttl) {
                foreach ($dirty as $field => $value) {
                    $stored = $value === null ? '__NULL__' : (string) $value;
                    $pipe->setex("{$prefix}:{$field}", $ttl, $stored);
                }
            });
        }
    }

    /**
     * Removes all cached keys for this model instance.
     */
    public function clearCache(): void
    {
        $prefix = $this->getCachePrefix();
        $fields = $this->cacheableFields();

        $keys = array_map(fn($f) => "{$prefix}:{$f}", $fields);
        $keys[] = "{$prefix}:__cached__";

        foreach ($this->cacheableRelations() as $relation) {
            $keys[] = "{$prefix}:rel:{$relation}";
        }

        Redis::del($keys);
    }

    /**
     * TTL in seconds. Override in model to customise.
     */
    public function cacheableTtl(): int
    {
        return 3600; // 1 hour default
    }

    protected function getCachePrefix(int|string|null $id = null): string
    {
        $id   = $id ?? $this->getKey();
        $app  = strtolower(preg_replace('/\s+/', '_', config('app.name')));
        $base = strtolower(class_basename($this));

        return "{$app}:{$base}:{$id}";
    }

    protected function cacheRelations(int $ttl): void
    {
        foreach ($this->cacheableRelations() as $relation) {
            if (!method_exists($this, $relation)) {
                continue;
            }

            // Use already-loaded relation to avoid extra queries
            $related = $this->relationLoaded($relation)
                ? $this->getRelation($relation)
                : $this->{$relation};

            if ($related === null) {
                continue;
            }

            $prefix = $this->getCachePrefix();

            // Normalize hasOne/belongsTo (single model) and hasMany/belongsToMany (collection)
            $ids = $related instanceof \Illuminate\Database\Eloquent\Model
                ? [$related->getKey()]
                : $related->modelKeys();

            if ($ttl < 0) {
                // Negative TTL means "cache indefinitely" — use set instead of setex
                Redis::set("{$prefix}:rel:{$relation}", json_encode($ids));
                continue;
            }

            Redis::setex("{$prefix}:rel:{$relation}", $ttl, json_encode($ids));
        }
    }

    /**
     * Retrieve related models, from cache if possible.
     *
     * Usage: $model->getCachedRelation('tags')
     */
    public function getCachedRelation(string $relation): \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
    {
        $prefix   = $this->getCachePrefix();
        $cacheKey = "{$prefix}:rel:{$relation}";

        try {
            if (!method_exists($this, $relation)) {
                throw new \InvalidArgumentException("Relation [{$relation}] does not exist on " . static::class);
            }

            $raw = Redis::get($cacheKey);

            if (!$raw) {
                $related = $this->{$relation};
                $this->cacheRelations($this->cacheableTtl());
                return $related;
            }

            $ids          = json_decode($raw, true);
            $relatedModel = $this->{$relation}()->getRelated();
            $useCache     = in_array(Cacheable::class, class_uses_recursive($relatedModel));

            $results = collect($ids)->map(
                fn($id) => $useCache
                    ? $relatedModel::findCacheable($id)
                    : $relatedModel::find($id)
            )->filter();

            $isSingle = $this->{$relation}() instanceof \Illuminate\Database\Eloquent\Relations\HasOne
                || $this->{$relation}() instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo;

            return $isSingle
                ? $results->first()
                : new \Illuminate\Database\Eloquent\Collection($results);

        } catch (\Throwable $e) {
            report($e);
            return $this->{$relation};
        }
    }
}