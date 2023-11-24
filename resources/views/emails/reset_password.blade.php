@extends('layouts.email_layout')
@section('content')
<p>We have received a request to reset your password.</p>
<p>To reset your password, please follow the instructions below:</p><br>
<p>Click the following link to reset your password.</p>
@component('mail::button', ['url' => $url])
Reset Password
@endcomponent
<p>If you didn't request a password reset, please ignore this email. Your password will remain unchanged.</p><br>
@endsection
