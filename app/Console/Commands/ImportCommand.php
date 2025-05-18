<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Row;
use App\Models\Subtitle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class ImportCommand extends Command
{
    /** @var string */
    protected $signature = 'import:subtitles';

    /** @var string */
    protected $description = 'Import subtitles';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $subs1 = DB::table('subtitles_old')
            ->where('film_id', 12)
            ->where('language_id', 1)
            ->orderBy('position')
            ->get();

        $subs2 = DB::table('subtitles_old')
            ->where('film_id', 12)
            ->where('language_id', 2)
            ->orderBy('position')
            ->get();

        $subtitle = Subtitle::find(12);
        $data = [];

        for ($i = 0; $i < count($subs2); $i++) {
            $data[] = [
                'subtitle_id' => $subtitle->id,
                'text1' => $subs1[$i]->text ?? '',
                'text2' => $subs2[$i]->text ?? '',
                'start' => $sub1[$i]->start ?? $subs2[$i]->start ?? '',
                'end' => $subs1[$i]->end ?? $subs2[$i]->end ?? '',
                'position' => $i
            ];
        }

        Row::query()->insert($data);

        return self::SUCCESS;
    }
}
