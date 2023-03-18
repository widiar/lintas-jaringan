<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $month = date('n');
        $year = date('Y');
        $tanggal = 30;
        if ($month == 2) $tanggal = 28;
        $awalMonth = $month - 2;
        $awalYear = $year;
        if ($awalMonth < 1) {
            $awalMonth += 12;
            $awalYear -= 1;
        }
        $tanggal_awal = $year . '-' . $month . '-01';
        $tanggal_akhir = $year . '-' . $month . '-' . $tanggal;

        $pelanggan = Pelanggan::select(
            // DB::raw('YEAR(created_at) as year'),
            DB::raw('DATE(created_at) tanggal'),
            // DB::raw("DATE_FORMAT(created_at, '%Y-%m') as tanggal"),
            DB::raw('COUNT(*) as jumlah')
        )
            ->whereBetween('created_at', [$tanggal_awal . ' 00:00:00', $tanggal_akhir . ' 23:59:59'])
            ->groupBy('tanggal')
            ->get();
        // dd($pelanggan);
        $totalPelanggan = Pelanggan::count();

        $totalInvoice = Invoice::count();
        $invoice = Invoice::select(
            DB::raw('DATE(created_at) tanggal'),
            // DB::raw("DATE_FORMAT(created_at, '%Y-%m') as tanggal"),
            DB::raw('SUM(total_harga) as jumlah')
        )
            ->whereBetween('created_at', [$tanggal_awal . ' 00:00:00', $tanggal_akhir . ' 23:59:59'])
            ->where('status', 'PAID')
            ->groupBy('tanggal')
            ->get();
        // dd($invoice);

        $totalTicket = Ticket::count();
        $totalPaket = Paket::count();
        return view('admin.dashboard', compact(
            'pelanggan',
            'totalPelanggan',
            'totalInvoice',
            'invoice',
            'totalTicket',
            'totalPaket'
        ));
    }

    public function filterPelanggan(Request $request)
    {
        $pelanggan = Pelanggan::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('COUNT(*) as jumlah')
        )->whereBetween('created_at', [$request->tanggal_awal . ' 00:00:00', $request->tanggal_akhir . ' 23:59:59'])
            ->groupBy('tanggal')->get();
        return response()->json([
            'status' => 'success',
            'data' => $pelanggan
        ]);
    }
    public function filterInvoice(Request $request)
    {
        $inv = Invoice::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('SUM(total_harga) as jumlah')
        )->whereBetween('created_at', [$request->tanggal_awal . ' 00:00:00', $request->tanggal_akhir . ' 23:59:59'])
            ->where('status', 'PAID')
            ->groupBy('tanggal')->get();
        return response()->json([
            'status' => 'success',
            'data' => $inv
        ]);
    }
}
