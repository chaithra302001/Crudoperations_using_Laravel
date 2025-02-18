<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get logged-in user

        return view('dashboard', compact('user')); // No need for `with()` here
    }
}
