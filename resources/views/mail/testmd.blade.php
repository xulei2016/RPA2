@component('mail::message')

<h1>{{$mail->title}}</h1>

{!! $mail->content !!}

@endcomponent
