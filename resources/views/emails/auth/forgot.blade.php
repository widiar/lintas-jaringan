<x-mail::message>
# Reset Password Lintas Jaringan

Reset password anda dengan menekan tombol di bawah ini.

<x-mail::button :url="$url">
    Reset Password
</x-mail::button>

atau bisa menggunakan link ini <br><a href="{{$url}}" target="_blank" rel="noopener noreferrer">{{ $url }}</a>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>