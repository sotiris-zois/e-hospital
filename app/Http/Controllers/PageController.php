<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(){
        $path = public_path('pages/welcome.html');

        return file_get_contents($path);
    }

    public function loginForm(){
        $path = public_path('pages/login.html');

        return file_get_contents($path);
    }
}
