<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Film;

final class SerialsController extends Controller
{
    public function show(string $id): Film
    {
        /** @var Film $serial */
        $serial = Film::query()
            ->with('subtitles')
            ->find($id);

        return $serial;
    }

}
