<x-mail::message>
# Perubahan Tanggal Pemasangan

Hello {{ $user->pelanggan->nama }}, <br>
Kami ingin menginformasikan bahwa terdapat perubahan pada rencana tanggal pasang {{ $data->nama_paket }} 
dari tanggal <b>{{ strftime("%e %B %Y", strtotime($data->tanggal_pasang)) }}</b> menjadi <b>{{ strftime("%e %B %Y", strtotime($data->reschedule)) }}</b>

dengan keterangan : {{ $data->keterangan_res }}

<br>
Pastikan bahwa tanggal tersebut anda berada dirumah.
<br>




Terimakasih atas kepercayaan anda kepada kami.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
