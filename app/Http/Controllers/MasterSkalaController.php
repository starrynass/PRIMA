<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterSkalaController extends Controller
{
    public function index()
        {
            $skalaNilai = [];
            $predikatNilai = [];

            return view('master.skala-predikat.index', compact('skalaNilai', 'predikatNilai'));
        }
}
