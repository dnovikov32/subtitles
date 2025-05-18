<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Row;
use App\Models\Film;

/**
 * @property integer $id
 * @property integer $film_id
 * @property string $title
 * @property integer $season
 * @property integer $episode
 * @property integer status
 *
 * @property Row[] $rows
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
