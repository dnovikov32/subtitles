<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $subtitle_id
 * @property string $text1
 * @property string $text2
 * @property string $start
 * @property string $end
 * @property integer $position
 */
final class Row extends Model
{
    public function toArray(): array
    {
        return [
            'text1' => $this->text1,
            'text2' => $this->text2,
            'position' => $this->position,
            'startTimeFormatted' => \DateTime::createFromFormat('Y-m-d H:i:s', $this->start)->format(),
            'status' => 0
        ];
    }
}
