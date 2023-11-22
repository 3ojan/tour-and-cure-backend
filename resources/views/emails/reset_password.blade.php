@component('mail::message')
# Greetings,
We have received a request to reset your password.
To reset your password, please follow the instructions below:

Click the following link to reset your password.
@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

If you didn't request a password reset, please ignore this email. Your password will remain unchanged.

Regards,
{{ config('app.name') }}
@endcomponent
