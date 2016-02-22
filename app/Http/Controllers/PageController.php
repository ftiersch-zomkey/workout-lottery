<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function getIndex() {
        return view('page.index');
    }
}
