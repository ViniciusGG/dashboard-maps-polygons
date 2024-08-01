@component('mail::message')

<span style="color: #282A74; font-size: 17px; font-weight: bold">{{ __('mail.report.intro') }} </span> 
{{ $user->name}}

<span style="color: #282A74; font-size: 17px; font-weight: bold">{{ __('mail.report.description') }} </span>
 {{ $report->description }}

<div>
    @foreach ($report->reportMedia as $media)
        @if ($media->type == 'image')
            <img src="{{ $media->url }}" alt="image" style="width: 100%; height: auto; margin: 1rem 0rem;">
        @endif
    @endforeach
</div>
<div style="text-align: center; margin: 1rem 0rem;">

@if ($video = $report->reportMedia()->videos()->first())
<div>
    <a href="{{$video->url}}" class="button"
           target="_blank" rel="noopener"
           style="background: linear-gradient(90deg, #00C6FF 0%, #0072FF 100%); border: 0; padding: 16px 40px; border-radius: 1rem; font-weight: bold; ">
Link Video
</a>
</div>
@endif
</div>

{{__('mail.invite.footer_1')}} <u style="color: #E8228B">azulfy.com</u>
<br><br>
{{__('mail.invite.footer_2')}}
<br><br>
{{__('mail.invite.footer_3')}}

@endcomponent
