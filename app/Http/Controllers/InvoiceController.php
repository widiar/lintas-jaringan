<?php

namespace App\Http\Controllers;

use App\Mail\DoneInvocieMail;
use App\Mail\PerubahanTanggalPasangMail;
use App\Models\Invoice;
use App\Models\Teknisi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Invoice::where('status', 'PENDING')
        //     ->whereDate('created_at', '<', Carbon::now()->subDay())
        //     ->update(['status' => 'EXPIRED']);
        $user = Auth::user();
        if ($request->ajax()) {
            if ($user->hasRole('Pelanggan')) {
                $data = Invoice::with('user', 'user.pelanggan')->where('user_id', $user->id);
            } else if ($user->hasRole('Teknisi')) {
                $user = User::with('teknisi')->where('id', $user->id)->first();
                $data = Invoice::with('user', 'user.pelanggan')->where('teknisi_id', $user->teknisi->id);
            } else {
                $data = Invoice::with('user', 'user.pelanggan');
            }
            if (!is_null($request->status)) $data->where('status', $request->status);
            $data->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['inv_number']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addColumn('status', function ($row) {
                    if ($row['status'] == 'PAID') return '<span class="badge badge-success">' . $row['status'] . '</span>';
                    else if ($row['status'] == 'PENDING') return '<span class="badge badge-warning">' . $row['status'] . '</span>';
                    else if ($row['status'] == 'PROSES') return '<span class="badge badge-info">' . $row['status'] . '</span>';
                    else if ($row['status'] == 'RESCHEDULE') return '<span class="badge badge-warning">' . $row['status'] . '</span>';
                    else if ($row['status'] == 'DONE') return '<span class="badge badge-success">' . $row['status'] . '</span>';
                    else return '<span class="badge badge-danger">' . $row['status'] . '</span>';
                })
                ->addColumn('tanggal_pesan', function ($row) {
                    return strftime("%e %B %Y", strtotime($row['created_at']));
                })
                ->addColumn('action', function ($row) {
                    $user = Auth::user();
                    $urlShow    = ' <a href="' . route('invoice.show', Hashids::encode($row['id'])) . '" class="btn btn-primary btn-xs"><i class="fas fa-eye right"></i> View</a> ';
                    $urlEdit    = ' <a href="' . route('invoice.edit', Hashids::encode($row['id'])) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen right"></i> Edit</a> ';

                    $urlDestroy = '';
                    if ($user->hasRole('super admin')) $urlDestroy = '<a href="' . route('invoice.destroy', Hashids::encode($row['id'])) . '" class="btn btn-danger btn-xs deleteData"><i class="fas fa-trash right"></i> Delete</a> ';

                    $urlPay = '';
                    if ($row['status'] == 'PENDING' && $user->hasRole('Pelanggan')) $urlPay = ' <a href="' . json_decode($row['xendit'])->invoice_url . '" class="btn btn-secondary btn-xs"><i class="fas fa-dollar-sign right"></i> Bayar</a> ';

                    $urlPrint = ' <a href="' . route('invoice.print', Hashids::encode($row['id'])) . '" class="btn btn-info btn-xs"><i class="fas fa-print right"></i> Print</a> ';
                    if ($row['status'] == 'EXPIRED') $urlPrint = '';

                    $btn = (auth()->user()->can('detail_invoice') ? $urlShow : '') . (auth()->user()->can('edit_invoice') ? $urlEdit : '') . (auth()->user()->can('delete_invoice') ? $urlDestroy : '') . (auth()->user()->can('print_invoice') && $row['status'] == 'PENDING' ? '' : $urlPrint) . $urlPay;
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.invoice.index');
    }

    public function show($id)
    {
        $data = Invoice::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        return view('admin.invoice.show', compact('data'));
    }

    public function print($id)
    {
        $inv = Invoice::where('id', Hashids::decode($id))->first();
        if (is_null($inv)) abort(404);
        return view('pdf.invoice', compact('inv'));
    }

    public function edit($id)
    {
        $data = Invoice::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        $teknisi = null;
        if (!is_null($data->teknisi_id)) {
            $teknisi = Teknisi::find($data->teknisi_id);
        }
        return view('admin.invoice.edit', compact('data', 'teknisi'));
    }

    public function listTeknisi(Request $request)
    {
        $search = $request->search;
        $teknisis = Teknisi::where('nama', 'like', "%$search%")->get(['id', 'nohp', 'nama']);
        $data = [];
        foreach ($teknisis as $teknisi) {
            $dt = [
                'id' => $teknisi->id,
                'text' => $teknisi->nama,
                'nohp' => $teknisi->nohp
            ];
            array_push($data, $dt);
        }
        return $data;
    }

    public function update(Request $request, $id)
    {
        $data = Invoice::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        // dd($request->all());
        $error = 0;
        if ($data->status != $request->status) {
            if ($data->status == 'DONE') $error = 1;
            if (($data->status == 'PROSES') && ($request->status == 'PAID' || $request->status == 'PENDING')) $error = 1;
            if (($data->status == 'RESCHEDULE') && ($request->status == 'PAID' || $request->status == 'PENDING')) $error = 1;
            if ($data->status == 'PAID' && $request->status == 'PENDING') $error = 1;
        }
        if ($error == 1) {
            return response()->json(['status' => 'failed', 'message' => 'Status tidak dapat dirubah mundur']);
        }
        $user = User::where('id', $data->user_id)->first();
        if ($request->status == 'RESCHEDULE') {
            // if ($data->tanggal_pasang != $request->tanggal_pasang) {
            //     // send email bahwa ada perubahan tanggal pasang
            // }
            $data->keterangan_res = $request->keterangan_res;
            $data->reschedule = $request->reschedule;
            Mail::to($user->email)->send(new PerubahanTanggalPasangMail($data));
        }
        if ($request->status == 'PROSES') {
            if (!auth()->user()->hasRole('Teknisi'))
                $data->teknisi_id = $request->teknisi;
        }
        if ($request->status == 'DONE') {
            Mail::to($user->email)->send(new DoneInvocieMail($data));
        }
        $data->status = $request->status;
        $data->tanggal_pasang = $request->tanggal_pasang;
        $data->save();
        return response()->json(['status' => 'success', 'message' => 'Berhasil di update']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Invoice::where('id', Hashids::decode($id))->firstOrFail();
        if (is_null($data)) abort(404);
        $data->delete();
        return response()->json('Sukses');
    }
}
