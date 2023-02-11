<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Service;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $banners = Banner::get(['judul', 'sub_judul', 'deskripsi']);
        $services = Service::get(['judul', 'keterangan', 'gambar']);
        return view('site.index', compact('banners', 'services'));
    }
}
