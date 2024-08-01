<table style="margin: 1.4rem auto;">
    <tr>
        <td style="display: flex; gap: 30px;">
            <a href="{{ config('settings.mobile_app.app_fallback_url_android') }}"
                style="display: inline-block;">
                <img style="display: block; height: 100%; width: 200px;" src="{{ config('app.url') }}/images/store-google.png" alt="Store Google">
            </a>
            <a href="{{ config('settings.mobile_app.app_fallback_url_ios') }}"
                style="display: inline-block;">
                <img style="display: block; height: 100%; width: 200px;" src="{{ config('app.url') }}/images/store-apple.png"
                    class="" alt="Store Apple">
            </a>
        </td>
    </tr>
</table>
