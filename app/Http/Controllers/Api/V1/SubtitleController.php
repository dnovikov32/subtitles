<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Subtitle;
use App\Models\Row;

final class SubtitleController
{
    public function show(string $id): Subtitle
    {
        $subtitle = Subtitle::query()
            ->with(['film', 'rows'])
            ->findOrFail((int) $id);

        return $subtitle;
    }

    public function destroy(Request $request): array
    {
        Subtitle::query()->findOrFail((int) $request->get('id'))->delete();

        return ['status' => 'success'];
    }

    public function find($id)
    {
        $subtitle = Subtitle::with(['film'])->find($id);
        $rows = $this->formatRows($subtitle->rows);

        return [
            'subtitle' => $subtitle,
            'rows' => $rows
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        $subtitle = Subtitle::find($request->input('id'));
        $rows = json_decode($request->input('rows'));

        Row::where('subtitle_id', $subtitle->id)->delete();

        // TODO: repeat
        $subs1 = $rows->{Language::LANG_EN};
        $subs2 = $rows->{Language::LANG_RU};
        $maxLength = $this->getMaxLength($subs1, $subs2);
        $data = [];

        for ($i = 0; $i < $maxLength; $i++) {
            $start = $subs1[$i]->startTime ?? $subs2[$i]->startTime ?? 0;
            $end = $subs1[$i]->endTime ?? $subs2[$i]->endTime ?? 0;

            $data[] = [
                'subtitle_id' => $subtitle->id,
                'text1' => $subs1[$i]->text ?? '',
                'text2' => $subs2[$i]->text ?? '',
                'start' => $start,
                'end' => $end,
                'position' => $i
            ];
        }

        Row::insert($data);

        $subtitle->load('film');
        $rows = $this->formatRows($subtitle->rows);

        return [
            'subtitle' => $subtitle,
            'rows' => $rows,
        ];
    }

    // TODO: repeat
    private function getMaxLength($subs1, $subs2)
    {
        $count1 = count($subs1);
        $count2 = count($subs2);

        if ($count1 > $count2) {
            return $count1;
        }

        return $count2;
    }

    private function formatRows($rows)
    {
        $result = [];

        foreach ($rows as $index => $row) {
            /* @var Row $row */

            $result[Language::LANG_EN][$index] = [
                'text' => $row->text1,
                'startTime' => $row->start,
                'endTime' => $row->end,
            ];

            $result[Language::LANG_RU][$index] = [
                'text' => $row->text2,
                'startTime' => $row->start,
                'endTime' => $row->end,
            ];
        }

        return $result;
    }


//    /**
//     * @param Film $film
//     * @return array
//     */
//    private function findSubtitles(Film $film)
//    {
//        $langRu = Language::where('name', Language::LANG_RU)->first();
//        $langEn = Language::where('name', Language::LANG_EN)->first();
//
//        $subtitles = [];
//
//        $subtitles['ru'] = Subtitle::select('start', 'end', 'text', 'status')
//            ->where('film_id', $film->id)
//            ->where('language_id', $langRu->id)
//            ->orderBy('position')
//            ->get()
//            ->toArray();
//
//        $subtitles['en'] = Subtitle::select('start', 'end', 'text', 'status')
//            ->where('film_id', $film->id)
//            ->where('language_id', $langEn->id)
//            ->orderBy('position')
//            ->get()
//            ->toArray();
//
//        return $subtitles;
//    }
}
