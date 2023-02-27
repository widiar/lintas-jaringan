<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class PaketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $services = Paket::all();
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
                ->addColumn('keterangan', function ($row) {
                    return $row['is_show'] == 1 ? 'Tampil di Home' : 'Tidak Tampil di Home';
                })
                ->addColumn('action', function ($row) {
                    $urlShow    = ' <a href="' . route('admin.paket.show', Hashids::encode($row['id'])) . '" class="btn btn-primary btn-xs"><i class="fas fa-eye right"></i> View</a> ';
                    $urlEdit    = ' <a href="' . route('admin.paket.edit', Hashids::encode($row['id'])) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen right"></i> Edit</a> ';
                    $urlDestroy = ' <a href="' . route('admin.paket.destroy', Hashids::encode($row['id'])) . '" class="btn btn-danger btn-xs deleteData"><i class="fas fa-trash right"></i> Delete</a> ';
                    $btn = (auth()->user()->can('detail_paket') ? $urlShow : '') . (auth()->user()->can('edit_paket') ? $urlEdit : '') . (auth()->user()->can('delete_paket') ? $urlDestroy : '');
                    return $btn;
                })
                ->rawColumns(['action', 'icon'])
                ->make(true);
        }
        return view('admin.paket.index');
    }

    public function create()
    {
        return view('admin.paket.credit');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'judul' => 'required',
            'kecepatan' => 'required',
            'persen' => 'required',
            'fitue.*' => 'required',
            'show' => 'required',
            'active' => 'required'
        ]);
        $fitur = implode(";", $request->fitur);
        $data = Paket::create([
            'judul' => $request->judul,
            'kecepatan' => $request->kecepatan,
            'percent' => $request->persen,
            'fitur' => $fitur,
            'is_show' => $request->show,
            'is_active' => $request->active
        ]);
        if ($data)
            return to_route('admin.paket')->with('success', 'Data Berhasil Ditambahkan!');
        else
            return to_route('admin.paket')->with('error', 'Data Gagal Ditambahkan!');
    }

    public function show($id)
    {
        $data = Paket::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        return view('admin.paket.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Paket::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        return view('admin.paket.credit', compact('data', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'kecepatan' => 'required',
            'persen' => 'required',
            'fitue.*' => 'required',
            'show' => 'required',
            'active' => 'required'
        ]);
        $data = Paket::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        $fitur = implode(";", $request->fitur);
        $data->judul = $request->judul;
        $data->kecepatan = $request->kecepatan;
        $data->percent = $request->persen;
        $data->fitur = $fitur;
        $data->is_show = $request->show;
        $data->is_active = $request->active;
        $data->save();
        if ($data)
            return to_route('admin.paket')->with('success', 'Data Berhasil Diperbaharui!');
        else
            return to_route('admin.paket')->with('error', 'Data Gagal Diperbaharui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Paket::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        $data->delete();
        return response()->json('Sukses');
    }

    public function check(Request $request)
    {
        if (isset($request->id)) {
            $id = Hashids::decode($request->id);
            $count = Paket::where('is_active', 1)->where('is_show', 1)->where('id', '<>', $id)->count();
            if ($count >= 3 && $request->show == 1) return response()->json(false);
            else return response()->json(true);
        } else {
            // dd($request->all());
            $count = Paket::where('is_active', 1)->where('is_show', 1)->count();
            if ($count >= 3 && $request->show == 1) return response()->json(false);
            else return response()->json(true);
        }
    }
}
