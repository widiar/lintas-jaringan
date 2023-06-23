<?php

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('kabupaten', function(Request $request){
    $search = $request->search;
    $data = Kabupaten::where('nama', 'like', "%$search%")->get();
    $select2 = [];
    foreach ($data as $val) {
        $select2 [] = [
            'id' => $val->id,
            'text' => $val->nama,
        ];
    }
    return $select2;
})->name('api.kabupaten');

Route::get('kecamatan', function(Request $request){
    $search = $request->search;
    $data = Kecamatan::where('id_kabupaten', $request->idkab)->where('nama', 'like', "%$search%")->get();
    $lurah = Kelurahan::where('id_kabupaten', $request->idkab)->count();
    $select2 = [];
    foreach ($data as $val) {
        $select2 [] = [
            'id' => $val->id,
            'text' => $val->nama,
            'path' => $val->path
        ];
    }
    return [
        'select2' => $select2,
        'lurah' => $lurah
    ];
})->name('api.kecamatan');

Route::get('kelurahan', function(Request $request){
    $search = $request->search;
    $data = Kelurahan::where('id_kecamatan', $request->idkec)->where('nama', 'like', "%$search%")->get();
    $select2 = [];
    foreach ($data as $val) {
        $select2 [] = [
            'id' => $val->id,
            'text' => $val->nama,
            'path' => $val->path
        ];
    }
    return $select2;
})->name('api.kelurahan');
