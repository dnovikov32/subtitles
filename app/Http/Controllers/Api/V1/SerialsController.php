<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Film;

final class SerialsController
{
    public function show(string $id): Film
    {
        $serial = Film::query()
            ->with('subtitles')
            ->findOrFail((int) $id);

        return $serial;
    }

}
