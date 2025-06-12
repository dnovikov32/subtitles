<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Status;
use Benlipp\SrtParser\Exceptions\FileNotFoundException;
use Benlipp\SrtParser\Parser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Subtitle;
use App\Models\Row;
use Illuminate\Http\UploadedFile;

final class FilmsController
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
        $film = Film::query()
            ->with('subtitles')
            ->findOrFail($id);

        return $film;
    }

    public function show(int $id): Film
    {
        $film = Film::query()
            ->with(['subtitles' => function ($query) {
                $query->where('season')->with('rows');
            }])
            ->findOrFail($id);

        return $film;
    }

    public function edit(Request $request): Film
    {
        $film = Film::query()->find((int) $request->input('film.id'));

        if (!$film) {
            $film = new Film();
        }

        $film->title = $request->input('film.title');

        $film->save();

        $ruFile = $request->file('subtitle.enFile');
        $enFile = $request->file('subtitle.ruFile');


        if ($ruFile && $enFile) {
            $season = (int) $request->input('subtitle.season');
            $episode = (int) $request->input('subtitle.episode');

            Subtitle::query()
                ->where('film_id', $film->id)
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

            $ruSubs = $this->parseFile($ruFile);
            $enSubs = $this->parseFile($enFile);
            $maxLength = $this->getMaxLength($ruSubs, $enSubs);
            $rows = [];

            for ($i = 0; $i < $maxLength; $i++) {
                $rows[] = [
                    'subtitle_id' => $subtitle->id,
                    'text1' => isset($ruSubs[$i]->text) ? strip_tags($ruSubs[$i]->text) : '',
                    'text2' => isset($enSubs[$i]->text) ? strip_tags($enSubs[$i]->text) : '',
                    'start' => $ruSubs[$i]->startTime ?? $enSubs[$i]->startTime ?? '',
                    'end' => $ruSubs[$i]->endTime ?? $enSubs[$i]->endTime ?? '',
                    'position' => $i
                ];
            }

            Row::query()->insert($rows);
        }

        $film->load('subtitles');

        return $film;
    }

    public function destroy(Request $request): array
    {
        Film::query()->findOrFail((int) $request->get('id'))->delete();

        return ['status' => 'success'];
    }

    /**
     * @throws FileNotFoundException
     */
    private function parseFile(UploadedFile $file): array
    {
        $parser = new Parser();
        $parser->loadFile($file->getRealPath());

        return $parser->parse();
    }

    private function getMaxLength($subs1, $subs2): int
    {
        $count1 = count($subs1);
        $count2 = count($subs2);

        if ($count1 > $count2) {
            return $count1;
        }

        return $count2;
    }
}
