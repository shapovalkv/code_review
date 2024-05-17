<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('home', ['plans' => Plan::get()]);
    }
}
