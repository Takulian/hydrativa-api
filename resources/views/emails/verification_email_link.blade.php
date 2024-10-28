<x-mail::message>
# Verification Email


<x-mail::button :url="$url">
Verify
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
