<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @property integer $id
 * @property string $title
 */
final class Status
{
    const STATUS_DISABLE = 0;
    const STATUS_NEW = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_READY = 3;

}
