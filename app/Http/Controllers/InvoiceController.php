<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        if ($request->ajax()) {
            if ($user->hasRole('Pelanggan')) {
                $data = Invoice::where('user_id', $user->id)->get();
            } else {
                $data = Invoice::all();
            }
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
                    else return '<span class="badge badge-danger">' . $row['status'] . '</span>';
                })
                ->addColumn('tanggal_pesan', function ($row) {
                    return strftime("%e %B %Y", strtotime($row['created_at']));
                })
                ->addColumn('action', function ($row) {
                    $urlShow    = ' <a href="' . route('invoice.show', Hashids::encode($row['id'])) . '" class="btn btn-primary btn-xs"><i class="fas fa-eye right"></i> View</a> ';
                    // $urlEdit    = ' <a href="' . route('invoice.edit', Hashids::encode($row['id'])) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen right"></i> Edit</a> ';
                    // $urlDestroy = ' <a href="' . route('invoice.destroy', Hashids::encode($row['id'])) . '" class="btn btn-danger btn-xs deleteData"><i class="fas fa-trash right"></i> Delete</a> ';
                    $urlEdit    = '';
                    $urlDestroy = '';
                    $urlPrint = ' <a href="' . route('invoice.print', Hashids::encode($row['id'])) . '" class="btn btn-warning btn-xs"><i class="fas fa-print right"></i> Print</a> ';
                    $btn = (auth()->user()->can('detail_invoice') ? $urlShow : '') . (auth()->user()->can('edit_invoice') ? $urlEdit : '') . (auth()->user()->can('delete_invoice') ? $urlDestroy : '') . (auth()->user()->can('print_invoice') && $row['status'] == 'PAID' ? $urlPrint : '');
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
