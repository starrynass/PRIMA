<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class TemplatePenilaianController extends Controller
{
    public function index()
    {
        return view('master.template_penilaian');
    }
}
