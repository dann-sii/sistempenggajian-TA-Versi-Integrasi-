<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class GajiLemburController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('gaji_pokok.index');
    }
}
