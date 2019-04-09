@component('mail::message')

<h1>{{$mail->title}}</h1>

{!! $mail->content !!}

{{--@component('mail::button', ['url' => ""])--}}
{{--测试--}}
{{--@endcomponent--}}

@endcomponent
