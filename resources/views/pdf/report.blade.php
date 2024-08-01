<html>

<head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;500;600;700&display=swap');

        @font-face {
            font-family: 'Confortaa';
            src: url({{ storage_path('fonts/Comfortaa-Regular.ttf') }}) format("truetype");
            font-weight: 400;
        }

        @font-face {
            font-family: 'Confortaa';
            src: url({{ storage_path('fonts/Comfortaa-Medium.ttf') }}) format("truetype");
            font-weight: 500;
        }

        @font-face {
            font-family: 'Confortaa';
            src: url({{ storage_path('fonts/Comfortaa-SemiBold.ttf') }}) format("truetype");
            font-weight: 600;
        }

        @font-face {
            font-family: 'Confortaa';
            src: url({{ storage_path('fonts/Comfortaa-Bold.ttf') }}) format("truetype");
            font-weight: 700;
        }



        /** Define the margins of your page **/
        @page {
            margin: 70px 0 5px 0;

            @bottom-right {
                content: counter(page) " of " counter(pages);
            }
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        .page-break-auto {
            page-break-after: auto;
        }

        * {
            font-family: 'Confortaa', sans-serif;
            ';
 box-sizing: border-box;
        }

        /* TEXT SIZES */
        .xxl {
            font-size: 24px;
        }

        .xl {
            font-size: 20px;
        }

        .lg {
            font-size: 16px;
        }

        .md {
            font-size: 14px;
        }

        .sm {
            font-size: 12px;

        }

        .xs {
            font-size: 10px;

        }

        /* HEADER  */
        .header {
            position: fixed;
            top: -70px;
            left: 0px;
            right: 0px;
            height: 50px;
            display: -webkit-box;
            display: -webkit-flex;
            display: flex;
            -webkit-flex-direction: row;
            -webkit-justify-content: space-between;
            text-align: center;
            line-height: 35px;
            margin-bottom: 0px;
            border: 1px solid #dddd;
            background: #fff;
            padding-top: 21px;
        }

        .header .header__container {
            width: 100%;
            position: relative;
        }

        .header .header__logo-container {
            width: 100%;
            text-align: center;
        }

        .header .header__logo-container img {
            width: 155px;
        }

        .header .header__message-container {
            position: absolute;
            top: 0;
            right: 10px;
        }

        .header .header__message-container p {
            line-height: 10px;
            color: #757575;
        }

        /* RISK LEVEL - FIRST PAGE  */
        .default-color-bg {
            color: #E63137;
            background: rgba(230, 49, 55, 0.20);

        }

        .first-page-step-0 h1 {
            color: #E63137;
        }

        .first-page-step-0 .risk-level .risk-level__tag-risk {
            color: #E63137;
            background: rgba(230, 49, 55, 0.20);
        }



        .first-page-step-1 h1 {
            color: rgba(255, 196, 44, 1);
        }

        .first-page-step-1 .risk-level__tag-risk {
            color: rgba(255, 196, 44, 1);
            background: rgba(255, 196, 44, 0.20);
        }

        .first-page-step-2 h1 {
            color: rgba(57, 207, 82, 1);
        }

        .first-page-step-2 .risk-level__tag-risk {
            color: rgba(57, 207, 82, 1);
            background: rgba(57, 207, 82, 0.20);
        }


        .risk-level h1 {
            font-weight: 700;
            margin-top: 40px;
        }

        .risk-level h2 {
            font-weight: 700;
            margin-top: 10px;
        }

        .risk-level .risk-level__tag-risk {
            font-weight: 500;
            padding: 4px 8px;
            width: fit-content;
            margin-top: 34px;
            margin-bottom: 5px;
            width: 78px
        }

        .risk-level__info {
            display: block;
            position: relative;
            border-radius: 16px 16px 16px 16px;
            margin-top: 20px;
            background: #EFEFEF;
            padding-bottom: 20px;
            padding-top: 20px;
            padding-left: 20px;
            margin-left: 10px;
            margin-right: 10px;
        }

        .risk-level__info p {
            font-weight: bold;
        }

        .risk-level__info p span {
            font-weight: 400;
        }

        .risk-level__title p {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .risk-level__message {
            margin-top: 10px;
            padding-left: 20px;
            border-bottom: 1px solid #C1C1C1;
        }

        .risk-level__message p {
            font-weight: bold;
        }

        .risk-level__message p span {
            font-weight: 400;
        }

        .risk-level__content {
            display: block;
            width: 100%;
            height: 356px;
            position: relative;
            border-radius: 16px 0px 0px 16px;
            margin-top: 80px;
        }

        .risk-level .risk-level__background {
            background: #EFEFEF;
            position: absolute;
            width: 95%;
            height: 100%;
            z-index: 1;
            right: 0;
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
        }

        .risk-level .risk-level__content-container {
            z-index: 10;
        }


        .risk-level .risk-level__description-content {
            padding-left: 20px;
            display: block;
            width: 60%;
            float: left;

        }

        .risk-level .risk-level__resume-text-big {
            font-weight: 400;
            margin-top: 12px;
            max-width: 644px;
        }

        .risk-level .risk-level__resume-text-small {
            font-weight: 700;
            margin-top: 24px;
            max-width: 644px;
        }


        .risk-level .risk-level__image-content {
            float: right;
            margin-right: -50px;
        }

        .risk-level .risk-level__image-content img {
            height: 350px;
            margin-top: -38px;
        }


        .risk-level .risk-level__answer-description {
            position: relative;
            width: 100%;
            min-height: 400px;
            margin-top: 30px;
        }

        .risk-level__levels-title-container {
            padding-bottom: 0px;
            width: 100%;
            position: absolute;
        }

        .risk-level .risk-level__levels-title {
            font-weight: 700;
            text-align: center;
        }

        .risk-level .risk-level__farol-imgs-container {
            text-align: center;
            position: absolute;
            top: 30px;
        }



        .risk-level .risk-level__content-help {
            max-width: 235px;
            display: inline-block;
            margin-top: 80px;
        }

        .risk-level .risk-level__content-help img {
            width: 164px;
            height: 164px;
            object-fit: cover;

        }

        .risk-level .risk-level__content-help h4 {
            font-weight: 500;
            margin-top: -10px;
            text-align: center;
        }

        .risk-level .risk-level__help-minimal-description {
            margin-top: -40px !important;
        }

        .risk-level .risk-level__help-minimal-description p {
            font-weight: 700;
            margin-bottom: 24px;
        }

        /* ANSWERS PAGE */
        .answer-content__step-container {
            page-break-before: always;
        }

        .answer-content__step-container:first-child {
            page-break-before: auto;
        }

        .answer-content .answer-content__heading-container {
            text-align: center;
            margin-top: 20px;
        }

        .answer-content .answer-content__heading-container h1 {
            font-weight: 600;
            line-height: 38px;
        }

        .answer-content .answer-content__step-container {
            margin-top: 40px;
        }

        .answer-content .answer-content__step-container:first-child {
            margin-top: 20px;
        }



        .answer-content .answer-content__group-answer {
            margin-top: 42px;
            background: #fff;
            box-shadow: 0px 10px 50px rgba(0, 0, 0, 0.12);
            border-radius: 16px 0px 16px 16px;
            flex: 1;
        }

        .answer-content .answer-content__count-item {
            width: 48px;
            height: 48px;
            display: inline-block;
            font-weight: 600;
            font-size: 24px;
            color: white;
            background: black;
            margin-right: 14px;
            text-align: center;
            line-height: 35px;
        }

        .answer-content .answer-content__heading-text {
            display: inline-block;
            line-height: 0px;
        }

        .answer-content .answer-content__answers {
            padding: 20px 0px 20px 32px;
            border-radius: 16px;
            margin-top: 24px;
            position: relative;
        }

        .answer-content__questions-container {
            max-width: 55%;
        }

        .answer-content .answer-content__question-title {
            font-weight: 400;
        }

        .answer-content .answer-content__options {
            padding-right: 20px;
            max-width: 568px;
            height: fit-content;
            padding-left: 16px;
            display: flex;
            align-items: center;
            background: #EFEFEF;
            border: 1px solid #C1C1C1;
            margin-top: 16px;
        }

        .answer-content__options p {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .answer-content__options.active {
            border: 2px solid black !important;
        }

        .answer-content__semaphore-container {
            float: right;
            max-width: auto;
            margin-right: -18px;
            margin-top: 15px;
        }

        /** Semaphore image **/
        .answer-content .answer-content__semaphore-container img {
            object-fit: contain;
            width: 280px;
            height: 150px;
        }

        .answer-content .answer-content__green-semaphore-img {
            margin-right: 1px;
        }

        .answer-content .answer-content__red-semaphore-img {
            margin-right: -10px;
        }

        .answer-content .answer-content__icon-type--1 {
            background: #fff;
            border: 1px solid #C1C1C1;
        }

        .answer-content .answer-content__icon-type-0 {
            background: rgba(52, 207, 82, 0.1);
            border: 1px solid #34CF52;
        }

        .answer-content .answer-content__icon-type-1 {
            background: rgba(255, 196, 44, 0.1);
            border: 1px solid #FFC42C;
        }

        .answer-content .answer-content__icon-type-2 {
            background: rgba(230, 49, 55, 0.1);
            border: 1px solid #E63137;
        }

        .answer-content .answer-content__questions-response {
            max-width: 55%;
        }

        .answer-content .answer-content__questions-response p {
            font-weight: 700;
            font-size: 16px;
            line-height: 24px;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .answer-content .answer-content__questions-response span {
            color: #757575;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0px;
            right: 0px;
            height: 35px;
            border-top: 1px solid #dddd;
            text-align: center;
            line-height: 24px;
            color: #757575;
        }

        footer:after {
            content: counter(page);
        }


        .page-break {
            page-break-after: always;
        }

        .page-break-auto {
            page-break-after: auto;
        }

        .main-container {
            max-width: 90%;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <!-- Define header and footer blocks before your content -->
    <header class="header">
        <div class="header__container">
            <div class="header__logo-container">
                {{-- @if ($isLocal)
                    <img src="{{ 'http://localhost/storage/images/logo-azul-dark.png' }}" />
                @else
                    <img src="{{ storage_path('app/public/images/logo-azul-dark.png') }}" />
                    @endif --}}
                <img src="{{ storage_path('app/public/images/logo-azul-dark.png') }}" />
            </div>
            <div class="header__message-container">
                {{-- <p class="md">Data summary</p> --}}
            </div>
        </div>
    </header>

    <footer class="xs">
        Copyright &copy; {{ config('app.name') }} - {{ config('app.url') }} | Página:
    </footer>

    <main>
        <div class="risk-level">
            <table style="width: 100%; background-color: #EDEDED; padding: 20px 0px 20px 20px;">
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <p class="" style="color: #0072FF; font-weight: 700;">
                            {{ __('alert') }}: <span
                                style="color: black; font-weight: 500;">{{ $messages['alert']->name }}</span>
                        </p>
                        <p class="" style="color: #0072FF; font-weight: 700;">
                            {{ __('date') }}: <span
                                style="color: black; font-weight: 500;">{{ date('M, d, Y', strtotime($messages['alert']->alert_datetime)) }}</span>
                        </p>
                        {{-- @fix Inficador aqui --}}
                        <p class="" style="color: #0072FF; font-weight: 700;">
                            {{ __('type') }}: <span
                                style="color: black; font-weight: 500; text-transform: capitalize;">{{ $messages['alert']->filter->name }}</span>
                        </p>
                    </td>
                    <td style="width: 50%; vertical-align: top;">
                        <p class="" style="color: #0072FF; font-weight: 700;">
                            {{ __('latitude') }}: <span
                                style="color: black; font-weight: 500;">{{ $messages['alert']->lat }}</span>
                        </p>
                        <p class="" style="color: #0072FF; font-weight: 700;">
                            {{ __('longitude') }}: <span
                                style="color: black; font-weight: 500;">{{ $messages['alert']->lng }}</span>
                        </p>
                        <p class="" style="color: #0072FF; font-weight: 700;">
                            {{ __('status') }}:
                            <span
                                style="padding: 5px; border-radius: 5px; color: #000; font-weight: 500">{{ $messages['alert']->status->name }}</span>
                        </p>
                    </td>
                </tr>
            </table>
            <div class="risk-level__title">
                @if ($isShowMessages)
                    <p>All data with comments</p>
                @else
                    <p>Data Summary</p>
                @endif
            </div>
            @foreach ($messages['messages'] as $message)
                @if ($isShowMessages || count($message->attachments) > 0)
                    <div
                        style="background-color: #EDEDED; border-radius: 15px; padding: 10px; margin-bottom: 10px; width: 100%; max-width: 400px; margin-left: 10px; margin-right: 10px">
                        <p style="margin: 0; font-size: 12px; color: #888;">{{ $message->user->name }} -
                            {{ date('d/m/Y H:i:s', strtotime($message->created_at)) }}</p>
                        @if ($isShowMessages)
                            <p style="margin: 5px 0; font-size: 14px;">{{ strip_tags($message->message) }}</p>
                        @endif
                        @if (count($message->attachments) > 0)
                            @foreach ($message->attachments as $attachment)
                                @if ($attachment->file_type == 'image')
                                    <div style="padding-bottom: 10px;">
                                        <img src="{{ $attachment->file_name }}" alt="Imagem" class="attachment-image"
                                            style="border-radius: 8px; max-width: 250px">
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </main>
</body>

</html>
