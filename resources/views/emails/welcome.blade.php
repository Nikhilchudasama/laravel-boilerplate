@extends('emails.layouts.app')

@section('content')
<h1>Welcome to {{ config('app.name') }}!</h1>

<p>Hi {{ $user->name }},</p>

<p>
    Thank you for joining {{ config('app.name') }}! We're excited to have you on board.
</p>

<p>
    Your account has been successfully created. You can now log in and start exploring all the features we have to offer.
</p>

<div style="text-align: center;">
    <a href="{{ route('login') }}" class="button">
        Get Started
    </a>
</div>

<p>
    If you have any questions or need assistance, feel free to reach out to our support team.
</p>

<p>
    Best regards,<br>
    The {{ config('app.name') }} Team
</p>
@endsection