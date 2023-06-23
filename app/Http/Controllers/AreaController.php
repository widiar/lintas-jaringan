<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Str;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Area::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function($row){
                    if(is_null($row['id_kelurahan'])){
                        $nama = Kecamatan::where('id', $row['id_kecamatan'])->first()->nama;
                    }else{
                        $nama = Kelurahan::where('id', $row['id_kelurahan'])->first()->nama;
                    }
                    return $nama;
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['nama']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addColumn('action', function ($row) {
                    // $urlShow    = ' <a href="' . route('admin.area.show', Hashids::encode($row['id'])) . '" class="btn btn-primary btn-xs"><i class="fas fa-eye right"></i> View</a> ';
                    // $urlEdit    = ' <a href="' . route('admin.area.edit', Hashids::encode($row['id'])) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen right"></i> Edit</a> ';
                    $urlDestroy = ' <a href="' . route('admin.area.destroy', Hashids::encode($row['id'])) . '" class="btn btn-danger btn-xs deleteData"><i class="fas fa-trash right"></i> Delete</a> ';
                    $btn = (auth()->user()->can('delete_area') ? $urlDestroy : '');
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.area.index');
    }
    public function create()
    {
        $kabupaten = Kabupaten::all();
        return view('admin.area.credit', compact('kabupaten'));
    }

    public function store(Request $request)
    {
        if(is_null($request->kelurahan)){
            $path = Kecamatan::where('id', $request->kecamatan)->first()->path;
        }else{
            $path = Kelurahan::where('id', $request->kelurahan)->first()->path;
        }
        $data = Area::create([
            'id_kabupaten' => $request->kabupaten,
            'id_kecamatan' => $request->kecamatan,
            'id_kelurahan' => $request->kelurahan,
            'path' => $path
        ]);
        if($data) {
            return to_route('admin.area')->with('success', 'Data Berhasil Ditambah!');
        }
    }

    public function destroy($id)
    {
        $data = Area::where('id', Hashids::decode($id))->firstOrFail();
        if (is_null($data)) abort(404);
        $data->delete();
        return response()->json('Sukses');
    }
}
