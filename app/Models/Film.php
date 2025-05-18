<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property integer $id
 * @property string $title
 */
final class Film extends Model
{
    protected $fillable = [
        'id',
        'title',
    ];

    /**
     * @return HasMany<Subtitle>
     */
    public function subtitles(): HasMany
    {
        return $this->hasMany(Subtitle::class)
            ->orderBy('season')
            ->orderBy('episode');
    }

    public function subtitle(): HasOne
    {
        return $this->hasOne(Subtitle::class)
            ->where('season', 0)
            ->where('episode', 0);
    }

}
