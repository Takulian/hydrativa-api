@component('mail::message')
# Verifikasi Email Anda

Halo, {{ $name }}

Anda telah mendaftar pada HydraTiva. Klik tombol di bawah ini untuk verifikasi email anda:

@component('mail::button', ['url' => $url])
Verifikasi Email
@endcomponent

Jika Anda tidak meminta pengaturan ulang ini, Anda dapat mengabaikan email ini.

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
