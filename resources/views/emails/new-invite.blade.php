@component('mail::message')

# {{__('mail.message.hello')}} {{$user->name}},
{{__('mail.invite-alert.introduction',
[
'alertName' => $alert->name,
'workspaceName' => $workspace->name,
'AdminName' => $admin->name ?? 'Admin']
)}}
***
# {{__('Description')}}:

{{$alert->description}}

{{__('Name')}} : {{$indicator->name}}

{{__('Created at')}} : {{$alert->created_at->format('d/m/Y H:i:s')}}

{{__('Latitude')}} : {{$alert->lat}}

{{__('Longitude')}} : {{$alert->lng}}

{{__('Name Location')}} : {{$alert->name}}
***


## <br>{{__('mail.new-alert.introduction_access')}}

@component('mail::button', ['url' => $url])
{{__('mail.invite.go-to-platform')}}
@endcomponent

@endcomponent
