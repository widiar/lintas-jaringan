<x-mail::message>
# Pemasangan Selesai

Hello {{ $user->pelanggan->nama }}, <br>
Kami ingin menginformasikan bahwa pemasangan {{ $data->nama_paket }} dengan nomor invoice <b>{{ $data->inv_number }}</b> sudah selesai dilakukan.





Terimakasih atas kepercayaan anda kepada kami.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
