<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $film_id
 * @property string $title
 * @property integer $season
 * @property integer $episode
 * @property integer status
 *
 * @property-read Row[] $rows
 *
 * @method static Builder|static query()
 */
final class Subtitle extends Model
{
    public function rows()
    {
        return $this->hasMany(Row::class)
            ->orderBy('position');
    }

    public function film()
    {
        return $this->belongsTo(Film::class);
    }
}
