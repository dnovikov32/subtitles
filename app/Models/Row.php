<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $subtitle_id
 * @property string $text1
 * @property string $text2
 * @property string $start
 * @property string $end
 * @property integer $position
 *
 * @method static Builder|static query()
 */
final class Row extends Model
{

}
