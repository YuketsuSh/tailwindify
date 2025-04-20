<?php

namespace Azuriom\Plugin\Tailwindify\Controllers;

use Azuriom\Http\Controllers\Controller;

class TailwindifyHomeController extends Controller
{
    /**
     * Show the home plugin page.
     */
    public function index()
    {
        return view('tailwindify::index');
    }
}
