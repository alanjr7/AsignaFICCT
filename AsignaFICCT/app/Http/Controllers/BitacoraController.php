<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index()
    {
        $bitacoras = Bitacora::with('user')->latest()->get();
        return view('bitacora.index', compact('bitacoras'));
    }
}