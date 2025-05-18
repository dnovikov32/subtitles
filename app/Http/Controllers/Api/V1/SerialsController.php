<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Benlipp\SrtParser\Parser;
use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Language;
use App\Models\Subtitle;
use App\Models\Row;

class SerialsController extends Controller
{
    /**
     * @param integer $id
     * @return Film
     */
    public function show($id)
    {
        /* @var Film $film */
//        $serial = Film::with(['subtitles' => function ($query) {
//            $query->where('season')->with('rows');
//        }])->find($id);

        $serial = Film::with('subtitles')
//            ->where()
            ->find($id);



        return $serial;
    }

}
