<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class sentimentController extends Controller
{
    public function index()
    {
        return view('sentiments');
    }
}
