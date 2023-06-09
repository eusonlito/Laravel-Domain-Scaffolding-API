@extends ('mail.layout')

@section ('body')

<h1 style="text-align: center; font-size: 36px; line-height: 40px; margin-bottom: 24px;">{{ __('user-password-reset-fail-mail.title') }}</h1>
<p style="text-align: center; font-size: 20px; line-height: 28px; margin: 40px 100px;">{!! __('user-password-reset-fail-mail.message', ['email' => $data['email']]) !!}</p>

@stop
