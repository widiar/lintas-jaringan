<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $banners = Banner::all();
            return DataTables::of($banners)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['judul']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            if (Str::contains(Str::lower($row['sub_judul']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addColumn('action', function ($row) {
                    $urlShow    = ' <a href="' . route('admin.banner.show', Hashids::encode($row['id'])) . '" class="btn btn-primary btn-xs"><i class="fas fa-eye right"></i> View</a> ';
                    $urlEdit    = ' <a href="' . route('admin.banner.edit', Hashids::encode($row['id'])) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen right"></i> Edit</a> ';
                    $urlDestroy = ' <a href="' . route('admin.banner.destroy', Hashids::encode($row['id'])) . '" class="btn btn-danger btn-xs deleteData"><i class="fas fa-trash right"></i> Delete</a> ';
                    $btn = (auth()->user()->can('detail_banner') ? $urlShow : '') . (auth()->user()->can('edit_banner') ? $urlEdit : '') . (auth()->user()->can('delete_banner') ? $urlDestroy : '');
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.banner.index');
    }

    public function create()
    {
        return view('admin.banner.credit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'sub_judul' => 'required',
            'deskripsi' => 'required',
        ]);

        $data = Banner::create([
            'judul' => $request->judul,
            'sub_judul' => $request->sub_judul,
            'deskripsi' => $request->deskripsi
        ]);

        return to_route('admin.banner')->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function show($id)
    {
        $data = Banner::find(Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        return view('admin.banner.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Banner::find(Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        return view('admin.banner.credit', compact('data', 'id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'sub_judul' => 'required',
            'deskripsi' => 'required',
        ]);
        $data = Banner::find(Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        $data->judul = $request->judul;
        $data->sub_judul = $request->sub_judul;
        $data->deskripsi = $request->deskripsi;
        $data->save();
        return to_route('admin.banner')->with('success', 'Data Berhasil Diupdate!');
    }

    public function destroy($id)
    {
        $data = Banner::where('id', Hashids::decode($id))->firstOrFail();
        if (is_null($data)) abort(404);
        $data->delete();
        return response()->json('Sukses');
    }
}
