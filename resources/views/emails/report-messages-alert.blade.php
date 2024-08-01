@component('mail::message')

<span>{{ __('mail.report.hello') }}, </span>
<span style="color: #282A74; font-size: 17px; font-weight: bold">{{ $user->name}} </span>


<span>{{ __('mail.report.request') }}: </span>
<span style="color: #282A74; font-size: 17px; font-weight: bold">{{ $alert->name }} </span>



{{__('mail.invite.footer_1')}} <u style="color: #E8228B">azulfy.com</u>
<br><br>
{{__('mail.invite.footer_3')}}

@endcomponent
