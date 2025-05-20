<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use Illuminate\Contracts\View\View;

final class AppController
{
    public function index(): View
    {
        return view('spa.index');
    }
}
