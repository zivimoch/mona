<?php

namespace App\Http\Controllers;

use App\Models\ShiftRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

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
        $shift_rules = ShiftRules::orderBy('urutan')->get();
        if (Auth::user()->jabatan == 'Sekretariat') {
            $view = 'rekap.absen';
        } else {
            $view = 'home';
        }
        return view($view)
                ->with('shift_rules', $shift_rules);
    }
}
