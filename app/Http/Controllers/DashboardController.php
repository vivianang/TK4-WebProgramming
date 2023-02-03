<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');    
        // Everything here needs to be authenticated
        
    }

    function index(Request $request) {
        return view('pages.dashboard');
    }
}
