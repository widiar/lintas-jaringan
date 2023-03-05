<x-mail::message>
# Pembayaran Berhasil

Hello {{ $user->pelanggan->nama }}, <br>
Tagihan anda dengan nomor <b>{{ $data->inv_number }}</b> telah berhasil dibayar. <br>

<x-mail::table>
| Deskripsi     | Jatuh tempo      | Harga          | PPN       |  Grand Total       |
| :------------ |:-------------:   | :----------:   | :------:  | :------------:     |
| {{ $data->nama_paket }}    | {{ strftime("%e %B %Y", strtotime($data->jatuh_tempo)) }} | Rp {{ number_format($data->harga, 2, ',', '.') }}  | 11%       | Rp {{ number_format($data->total_harga, 2, ',', '.') }}       |

</x-mail::table>

Email ini merupakan bukti pembayaran yang sah. <br>
Terimakasih atas kepercayaan anda kepada kami.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
