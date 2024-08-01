@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.frontend_url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}


{{-- Footer --}}
@slot('footer')
@component('mail::footer')
{{-- Centoscan platform - Exclusively made for PTC Therapeutics.

© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.') --}}
@endcomponent
@endslot
@endcomponent
