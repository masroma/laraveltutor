<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // ini untuk index
    public function index()
    {
        return view('dashboard.index');
    }
}
