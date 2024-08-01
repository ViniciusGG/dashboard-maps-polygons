@component('mail::message')

# {{__('mail.message.hello')}} {{$user->name}},

{{__('mail.message.introduction')}} {{$workspace->name}},

{{__('mail.message.introduction_2')}}

@component('mail::button', ['url' => $url])
{{__('mail.invite.go-to-platform')}}
@endcomponent


@endcomponent
