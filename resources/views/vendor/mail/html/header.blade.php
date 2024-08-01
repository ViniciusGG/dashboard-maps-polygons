{{-- @php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

$url = Request::url();

@endphp
<tr>
    <td style="background-color: #005F79; display: flex; justify-content: space-between; align-items: center; padding: 10px 30px;">
        <h2 style="color: white; font-weight: 400; margin: 0;">

            <b>Subject:</b> {{ Str::contains($url, 'mailable') ? 'Welcome to DBS Mapping' : 'DBS Mapping Password Reset Request - Time Sensitive' }}
        </h2>
        <img style="display: block; height: 70px; width: 75px;"
                src="{{ config('app.url') }}/images/ptc.svg" alt="PTC Logo">
    </td>
</tr> --}}
<tr>
    <td class="header" style="background-color: white;">
        <a href="{{ $url }}" style="display: inline-block; text-align: center; font-size: 36px; color: #2F80ED; margin-top: 5rem;">
            <img style="display: block; margin: 20px auto;"
                src="{{ config('app.url') }}/storage/images/logo.png" class="logo" alt="Azulfy Logo">
            {{ $slot }}
        </a>
    </td>
</tr>
