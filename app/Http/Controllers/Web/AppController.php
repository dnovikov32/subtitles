<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use Illuminate\Contracts\View\View;

final class AppController
{
    public function index(): View
    {
        dd('123');
        return view('spa.index');
    }
}
