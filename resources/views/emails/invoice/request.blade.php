<x-mail::message>
# Selesaikan Tagihan

Hello {{ $user->pelanggan->nama }}, <br>
Tagihan anda pada bulan ini telah dibuat dengan nomor invoice <b>{{ $data->inv_number }}</b>. <br>
Segera lakukan pembayaran sebelum tanggal <b>{{ strftime("%e %B %Y", strtotime($data->jatuh_tempo)) }}</b>

<x-mail::table>
| Deskripsi     | Jatuh tempo      | Harga          | PPN       |  Grand Total       |
| :------------ |:-------------:   | :----------:   | :------:  | :------------:     |
| {{ $data->nama_paket }}    | {{ strftime("%e %B %Y", strtotime($data->jatuh_tempo)) }} | Rp {{ number_format($data->harga, 2, ',', '.') }}  | 11%       | Rp {{ number_format($data->total_harga, 2, ',', '.') }}       |

</x-mail::table>

Segera bayar tagihan anda melalui link dibawah ini.
<x-mail::button :url="json_decode($data->xendit)->invoice_url">
Bayar
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
