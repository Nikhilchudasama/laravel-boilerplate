@extends('emails.layouts.app')

@section('content')
<h1>Reset Your Password</h1>

<p>Hi {{ $user->name }},</p>

<p>
    You recently requested to reset your password for your {{ config('app.name') }} account.
    Click the button below to proceed.
</p>

<div style="text-align: center;">
    <a href="{{ $resetUrl }}" class="button">
        Reset Password
    </a>
</div>

<p style="font-size: 14px; color: #718096;">
    This password reset link will expire in {{ config('auth.passwords.users.expire') }} minutes.
</p>

<p>
    If you didn't request a password reset, please ignore this email or contact support if you have concerns.
</p>

<p style="font-size: 12px; color: #a0aec0; margin-top: 30px;">
    If you're having trouble clicking the button, copy and paste the URL below into your web browser:
    <br>
    <a href="{{ $resetUrl }}" style="color: #667eea; word-break: break-all;">{{ $resetUrl }}</a>
</p>

<p>
    Best regards,<br>
    The {{ config('app.name') }} Team
</p>
@endsection