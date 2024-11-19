@component('mail::message')
# Reset Kata Sandi

Halo,

Anda telah meminta untuk mengatur ulang kata sandi Anda. Klik tombol di bawah ini untuk mengatur ulang kata sandi:

@component('mail::button', ['url' => $url])
Atur Ulang Kata Sandi
@endcomponent

Jika Anda tidak meminta pengaturan ulang ini, Anda dapat mengabaikan email ini.

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
