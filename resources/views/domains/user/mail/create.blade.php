@extends ('mail.layout')

@section ('body')

<h1 style="text-align: center; font-size: 36px; line-height: 40px; margin-bottom: 24px;">{{ __('user-create-mail.title', ['name' => $row->name]) }}</h1>
<p style="text-align: center; font-size: 20px; line-height: 28px; margin: 40px 100px;">{!! __('user-create-mail.message') !!}</p>
<p style="text-align: center; font-size: 20px; line-height: 28px; margin: 40px auto;"><span style="text-decoration: none; background: #0f2954; color: white; padding: 10px 15px; border-radius: 10px;">{{ $userCode->code }}</span></p>
<p style="text-align: center; font-size: 14px; line-height: 18px; margin: 80px 100px 40px 100px; color: #999">{{ __('user-create-mail.deleted') }}</p>

@stop
