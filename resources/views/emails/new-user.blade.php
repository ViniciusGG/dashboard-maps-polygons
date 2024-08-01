@component('mail::message')

# {{__('mail.invite.hello')}} {{$user->name}},

{{__('mail.invite.introduction')}}

{{__('mail.invite.introduction_2')}}<br>
{{__('mail.invite.introduction_3')}}


{{__('mail.invite.introduction_4')}} {{$password}}

@component('mail::button', ['url' => url(config('app.frontend_url').'/login')])
{{__('mail.invite.go-to-platform')}}
@endcomponent


{{__('mail.invite.footer_1')}} <u style="color: #E8228B">azulfy.com</u>
<br><br>
{{__('mail.invite.footer_2')}}
<br><br>
{{__('mail.invite.footer_3')}}

@endcomponent
