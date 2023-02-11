<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $services = Service::all();
            return DataTables::of($services)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['judul']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            if (Str::contains(Str::lower($row['keterangan']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addColumn('icon', function ($row) {
                    return '<img src="' . Storage::url('service/icon/') . $row['gambar'] . '" alt="" class="img-icon">';
                })
                ->addColumn('action', function ($row) {
                    $urlShow    = ' <a href="' . route('admin.service.show', Hashids::encode($row['id'])) . '" class="btn btn-primary btn-xs"><i class="fas fa-eye right"></i> View</a> ';
                    $urlEdit    = ' <a href="' . route('admin.service.edit', Hashids::encode($row['id'])) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen right"></i> Edit</a> ';
                    $urlDestroy = ' <a href="' . route('admin.service.destroy', Hashids::encode($row['id'])) . '" class="btn btn-danger btn-xs deleteData"><i class="fas fa-trash right"></i> Delete</a> ';
                    $btn = (auth()->user()->can('detail_service') ? $urlShow : '') . (auth()->user()->can('edit_service') ? $urlEdit : '') . (auth()->user()->can('delete_service') ? $urlDestroy : '');
                    return $btn;
                })
                ->rawColumns(['action', 'icon'])
                ->make(true);
        }
        return view('admin.service.index');
    }

    public function create()
    {
        return view('admin.service.credit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'keterangan' => 'required',
            'icon' => 'required'
        ]);
        // {{ Storage::url('gambar/' . $book->image) }}
        $gambar = $request->icon;
        $service = Service::create([
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'gambar' => $gambar->hashName(),
        ]);
        if ($service) {
            $gambar->storeAs('public/service/icon', $gambar->hashName());
            return to_route('admin.service')->with('success', 'Data Berhasil Ditambahkan!');
        } else {
            return to_route('admin.service')->with('error', 'Data Gagal Ditambahkan!');
        }
    }

    public function show($id)
    {
        $data = Service::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        return view('admin.service.show', compact('data'));
    }

    public function edit($id)
    {
        $data = Service::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        return view('admin.service.credit', compact('data', 'id'));
    }

    public function update(Request $request, $id)
    {
        $data = Service::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        $request->validate([
            'judul' => 'required',
            'keterangan' => 'required',
        ]);
        $data->judul = $request->judul;
        $data->keterangan = $request->keterangan;
        $img = $request->file('icon');
        if ($img) {
            Storage::disk('public')->delete('service/icon/' . $data->gambar);
            $img->storeAs('public/service/icon', $img->hashName());
            $data->gambar = $img->hashName();
        }
        $data->save();
        return to_route('admin.service')->with('success', 'Data Berhasil Diupdate!');
    }

    public function destroy($id)
    {
        $data = Service::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        Storage::disk('public')->delete('service/icon/' . $data->gambar);
        $data->delete();
        return response()->json('Sukses');
    }
}
