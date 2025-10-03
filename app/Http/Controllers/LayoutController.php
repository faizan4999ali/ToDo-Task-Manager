<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function Layout()
    {
        return view('/layouts/mainlayout');
    }
}
