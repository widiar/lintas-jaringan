<x-mail::message>
# Aktivasi Akun Lintas Jaringan

Aktifkan akun anda dengan menekan tombol di bawah ini.

<x-mail::button :url="$url">
    Aktivasi
</x-mail::button>

atau bisa menggunakan link ini <br><a href="{{$url}}" target="_blank" rel="noopener noreferrer">{{ $url }}</a>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>