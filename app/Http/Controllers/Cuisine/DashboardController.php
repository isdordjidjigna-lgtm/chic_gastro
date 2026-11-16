<?php

namespace App\Http\Controllers\Cuisine;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('cuisine.dashboard');
    }
}
