<?php

namespace App\Traits;

use App\Favorite;
use Illuminate\Database\Eloquent\Model;

trait Favoritable
{
    /**
     * Boot the trait.
     */
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    /**
     * A reply can be favorited.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * Favorite the current model.
     *
     * @return Model
     */
    public function favorite()
    {
        $maxOrder = auth()->user()->favorites()->max('order');
        $attributes = ['user_id' => auth()->id(), 'order' => $maxOrder + 1];

        if (! $this->favorites()->where($attributes)->exists()) {
            $this->favorites()->create($attributes);
        }
    }

    /**
     * Unfavorite the current model.
     */
    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        $this->favorites()->where($attributes)->get()->each->delete();
    }
}
