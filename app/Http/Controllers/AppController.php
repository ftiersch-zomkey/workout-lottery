<?php

namespace App\Http\Controllers;

class AppController extends Controller
{
    public function getIndex() {
        return view('app.index');
    }
}
