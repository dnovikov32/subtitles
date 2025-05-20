<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Language;
use App\Models\Subtitle;
use App\Models\Row;

final class FilmsController extends Controller
{
    public function index(): array
    {
        $films = Film::query()
            ->with('subtitle')
            ->has('subtitle')
            ->get();

        $serials = Film::query()
            ->whereHas('subtitles', function (Builder $query) {
                $query
                    ->where('season', '!=', 0)
                    ->where('episode', '!=', 0);
            })
            ->get();

        return [
            'films' => $films,
            'serials' => $serials,
        ];
    }

    public function find(string $id): Film
    {
        /* @var Film $film */
        $film = Film::query()
            ->with('subtitles')
            ->findOrFail($id);

        return $film;
    }

    /**
     * @param integer $id
     * @return Film
     */
    public function show($id)
    {
        /* @var Film $film */
        $film = Film::with(['subtitles' => function ($query) {
            $query->where('season')->with('rows');
        }])->find($id);

        return $film;
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return Film
     */
    public function edit(Request $request)
    {
        $film = Film::find($request->input('film.id'));

        if (! $film) {
            $film = new Film();
        }

        $film->title = $request->input('film.title');
        $film->save();

        $file1 = $request->file(Language::LANG_EN);
        $file2 = $request->file(Language::LANG_RU);

        if ($file1 && $file2) {
            $season = (int)$request->input('subtitle.season');
            $episode = (int) $request->input('subtitle.episode');

            Subtitle::where('film_id', $film->id)
                ->where('season', $season)
                ->where('episode', $episode)
                ->delete();

            $subtitle = new Subtitle();
            $subtitle->film_id = $film->id;
            $subtitle->title = $request->input('subtitle.title');
            $subtitle->season = $season;
            $subtitle->episode = $episode;
            $subtitle->status = Status::STATUS_NEW;

            $subtitle->save();

            $subs1 = $this->parseFile($file1);
            $subs2 = $this->parseFile($file2);
            $maxLength = $this->getMaxLength($subs1, $subs2);
            $data = [];

            for ($i = 0; $i < $maxLength; $i++) {
                $data[] = [
                    'subtitle_id' => $subtitle->id,
                    'text1' => isset($subs1[$i]->text) ? strip_tags($subs1[$i]->text) : '',
                    'text2' => isset($subs2[$i]->text) ? strip_tags($subs2[$i]->text) : '',
                    'start' => $subs1[$i]->startTime ?? $subs2[$i]->startTime ?? '',
                    'end' => $subs1[$i]->endTime ?? $subs2[$i]->endTime ?? '',
                    'position' => $i
                ];
            }

            Row::insert($data);
        }

        $film->load('subtitles');

        return $film;
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return array
     */
    public function destroy(Request $request)
    {
        Film::find($request->id)->delete();

        return ['status' => 'success'];
    }


    private function parseFile($file)
    {
        $parser = new Parser();
        $parser->loadFile($file->getRealPath());

        return $parser->parse();
    }

    private function getMaxLength($subs1, $subs2)
    {
        $count1 = count($subs1);
        $count2 = count($subs2);

        if ($count1 > $count2) {
            return $count1;
        }

        return $count2;
    }




}
