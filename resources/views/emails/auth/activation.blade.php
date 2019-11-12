@component('mail::message')
# One More Step

Welcome {{ $user->name }}, we just need you to confirm that your mail is valid.Got it? Coool.Please follow the link below to activate your account.

@component('mail::button', ['url' => route("register.activate", ["token" => $user->confirmation_token])])
Activate My Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
