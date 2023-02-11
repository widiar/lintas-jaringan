<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $banners = Banner::get(['judul', 'sub_judul', 'deskripsi']);
        return view('site.index', compact('banners'));
    }
}
