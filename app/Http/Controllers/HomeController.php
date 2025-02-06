<?php

namespace App\Http\Controllers;

use App\Models\ShiftRules;
use Illuminate\Http\Request;
// use Stevebauman\Location\Facades\Location;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $shift_rules = ShiftRules::get();
        return view('home')
                ->with('shift_rules', $shift_rules);
    }
}
