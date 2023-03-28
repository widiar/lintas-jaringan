<?php

namespace App\Http\Controllers;

use App\Events\ChatSend;
use App\Models\DetailTicket;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($request->ajax()) {
            if ($user->hasRole('Pelanggan')) {
                $data = Ticket::with('user')->where('user_id', $user->id)->get();
            } else {
                // $data = Ticket::where('user_admin', $user->id)->get();
                $data = Ticket::with('detail', 'user')->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['names']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addColumn('status', function ($row) {
                    if ($row['is_closed'] == 0) return '<span class="badge badge-success">OPEN</span>';
                    else return '<span class="badge badge-danger">CLOSED</span>';
                })
                ->addColumn('action', function ($row) {
                    $urlShow    = ' <a href="' . route('ticket.show', Hashids::encode($row['id'])) . '" class="btn btn-primary btn-xs"><i class="fas fa-eye right"></i> View</a> ';
                    $urlEdit    = ' <a href="' . route('ticket.edit', Hashids::encode($row['id'])) . '" class="btn btn-warning btn-xs"><i class="fas fa-pen right"></i> Reply</a> ';
                    $urlDestroy = ' <a href="' . route('ticket.destroy', Hashids::encode($row['id'])) . '" class="btn btn-danger btn-xs closeData"><i class="fas fa-trash right"></i> Close</a> ';
                    // $urlEdit    = '';
                    // $urlDestroy = '';
                    $urlPrint = ' <a href="' . route('ticket.print', Hashids::encode($row['id'])) . '" class="btn btn-warning btn-xs"><i class="fas fa-print right"></i> Print</a> ';
                    $btn = (auth()->user()->can('detail_ticket') ? $urlShow : '') . (auth()->user()->can('edit_ticket') && $row['is_closed'] == 0 ? $urlEdit : '') . (auth()->user()->can('delete_ticket') && $row['is_closed'] == 0 ? $urlDestroy : '');
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.ticket.index');
    }

    public function show($id)
    {
        $data = Ticket::with('detail')->where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        $user = Auth::user();
        $akses = 1;
        if (!$user->hasRole('Pelanggan')) {
            if (is_null($data->user_admin)) {
            } else {
                if ($user->id != $data->user_admin) $akses = 0;
            }
        }
        return view('admin.ticket.show', compact('data', 'id', 'akses'));
    }

    public function create()
    {
        $akses = 1;
        $id = null;
        return view('admin.ticket.credit', compact('akses', 'id'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $rand = Str::random(4);
        $ticketNumber = 'T-' . date('dmy') . '-' . $rand;

        $cek = Ticket::where('names', $ticketNumber)->first();
        if (!is_null($cek)) $ticketNumber = '#' . Str::random();

        $user = Auth::user();
        $ticket = Ticket::create([
            'names' => $ticketNumber,
            'subject' => $request->subject,
            'user_id' => $user->id
        ]);
        $ticket->detail()->create([
            'user_id' => $user->id,
            'body' => $request->message
        ]);

        return response()->json([
            'status' => 'success',
            'data' => route('ticket.edit', Hashids::encode($ticket->id))
        ]);
    }

    public function edit($id)
    {
        $data = Ticket::with('detail')->where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);
        $user = Auth::user();
        $akses = 1;
        if (!$user->hasRole('Pelanggan')) {
            if (is_null($data->user_admin)) {
                $data->user_admin = $user->id;
                $data->save();
            } else {
                if ($user->id != $data->user_admin) $akses = 0;
            }
        }
        return view('admin.ticket.credit', compact('data', 'id', 'akses'));
    }

    public function update(Request $request, $id)
    {
        $data = Ticket::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);

        $user = Auth::user();
        $detail = DetailTicket::create([
            'ticket_id' => $data->id,
            'user_id' => $user->id,
            'body' => $request->message
        ]);
        ChatSend::dispatch($detail);
        if ($detail) {
            return response()->json([
                'status' => 'success',
                'data' => $detail
            ]);
        }
    }

    public function destroy($id)
    {
        $data = Ticket::where('id', Hashids::decode($id))->first();
        if (is_null($data)) abort(404);

        $data->is_closed = 1;
        $data->save();
        return response()->json('Sukses');
    }
}
