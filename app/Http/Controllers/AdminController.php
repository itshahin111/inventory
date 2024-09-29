<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    function adminPage()
    {
        return view('pages.admin.dashboard');
    }
}