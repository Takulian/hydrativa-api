<x-mail::message>
# Reset Password

<x-mail::button :url="$url">
Reset
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
