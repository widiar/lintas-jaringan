<?php

namespace App\Http\Controllers;

use App\Mail\PaidInvoiceMail;
use App\Mail\RequestInvoiceMail;
use App\Models\Banner;
use App\Models\Invoice;
use App\Models\Paket;
use App\Models\Saran;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Xendit\Invoice as XenditInvoice;
use Xendit\Xendit;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function index()
    {
        $banners = Banner::get(['judul', 'sub_judul', 'deskripsi']);
        $services = Service::get(['judul', 'keterangan', 'gambar']);
        $pakets = Paket::where('is_show', 1)->where('is_active', 1)->get();
        return view('site.index', compact('banners', 'services', 'pakets'));
    }

    public function paket($id)
    {
        $data = Paket::where('id', $id)->first();
        if (is_null($data)) abort(404);
        $user = Auth::user()->pelanggan;
        return view('site.paket', compact('data', 'user'));
    }

    public function beliPaket(Request $request)
    {
        // dd($request->all());
        $invoice_number = 'INV/' . date('dmy') . '/' . Str::random(5);
        // dd($invoice_number);
        $user = Auth::user();
        $paket = Paket::where('id', $request->id)->first();
        if (is_null($paket)) abort(405);
        $ppn = round($paket->harga * 0.11);
        $total = $paket->harga + $ppn;
        $now = Carbon::now();
        $jatuh_tempo = $now->addDays(15)->format('Y-m-d');
        $inv = Invoice::create([
            'inv_number' => $invoice_number,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nohp' => $request->nohp,
            'tanggal_pasang' => $request->tanggal,
            'user_id' => $user->id,
            'ppn' => $ppn,
            'harga' => $paket->harga,
            'total_harga' => $total,
            'nama_paket' => $paket->judul,
            'kecepatan' => $paket->kecepatan,
            'fitur' => $paket->fitur,
            'jatuh_tempo' => $jatuh_tempo,
        ]);
        // make invoice from xendit
        Xendit::setApiKey(env('XENDIT_SECRET_KEY'));
        $params = [
            'external_id' => $invoice_number,
            'amount' => $inv->total_harga,
            'description' => 'Pembayaran paket ' . $inv->nama_paket,
            'invoice_duration' => 1296000, //15 hari
            'customer' => [
                'given_names' => $inv->nama,
                'email' => $user->email
            ],
            'payer_email' => $user->email,
            'success_redirect_url' => route('thank-you', ['callback' => Crypt::encryptString($inv->id)]),
            'failure_redirect_url' => route('failed', ['callback' => Crypt::encryptString($inv->id)]),
            'currency' => 'IDR'
        ];
        $xenInv = XenditInvoice::create($params);
        $inv->xendit = json_encode($xenInv);
        $inv->save();
        Mail::to($user->email)->send(new RequestInvoiceMail($inv));
        return response()->json([
            'status' => 200,
            'data' => $xenInv
        ]);
    }

    public function thankyou(Request $request)
    {
        try {
            if (isset($request->callback)) {
                $id = Crypt::decryptString($request->callback);
                $inv = Invoice::where('id', $id)->first();
                if (is_null($inv)) return to_route('home');
                if ($inv->is_paid_mail == 0) {
                    $user = User::where('id', $inv->user_id)->first();
                    Mail::to($user->email)->send(new PaidInvoiceMail($inv));
                }
                $inv->status = 'PAID';
                $inv->is_paid_mail = 1;
                $inv->save();
                return view('site.thank-you');
            } else {
                return to_route('home');
            }
        } catch (\Throwable $th) {
            return to_route('home');
        }
    }
    public function failed(Request $request)
    {
        try {
            if (isset($request->callback)) {
                $id = Crypt::decryptString($request->callback);
                $inv = Invoice::where('id', $id)->first();
                if (is_null($inv)) return to_route('home');
                $inv->status = 'FAILED';
                $inv->save();
                return view('site.failed');
            } else {
                return to_route('home');
            }
        } catch (\Throwable $th) {
            return to_route('home');
        }
    }

    // ----- DEBUG ONLY ----
    public function render()
    {
        $now = Carbon::now();
        // dd($now->addDays(15)->format('Y-m-d'));
        $data = Invoice::find(1);
        // dd(json_decode($data->xendit)->invoice_url);
        // return $pdf->stream();
        return (new PaidInvoiceMail($data))->render();
    }
    // ----- DEBUG ONLY -----

    public function saran(Request $request)
    {
        $saran = Saran::create([
            'name' => $request->name,
            'email' => $request->email,
            'masukan' => $request->masukan
        ]);

        if ($saran) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'failed'
            ]);
        }
    }
}
