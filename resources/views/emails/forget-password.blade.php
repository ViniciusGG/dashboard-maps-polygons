@component('mail::message')

# {{__('mail')['forget_password']['hello']}} {{$user->name}},

{{__('mail')['forget_password']['introduction']}}


@component('mail::button', ['url' => url(config('app.frontend_url').'/reset-password/?token='.$token.'&email='.$user->email)])

{{__('mail')['forget_password']['subject']}}

@endcomponent

{{__('mail')['forget_password']['footer_1']}} <u style="color: #2F80ED">azulfy.com</u>
<br><br>
{{__('mail')['forget_password']['footer_2']}}
<br><br>
{{__('mail')['forget_password']['footer_3']}}

@endcomponent
