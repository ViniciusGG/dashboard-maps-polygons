<?php

namespace App\Providers;

use App\Listeners\LogSentMessage;
use App\Listeners\RedirectDevEnvironmentEmails;
use App\Models\Alert;
use App\Models\AlertMessage;
use App\Models\AlertMessageAttachment;
use App\Models\Indicator;
use App\Models\SupportMessage;
use App\Models\Workspace;
use App\Observers\AlertMessageAttachmentObserver;
use App\Observers\AlertMessageObserver;
use App\Observers\AlertObserver;
use App\Observers\IndicatorObserver;
use App\Observers\SupportMessageObserver;
use App\Observers\WorkspaceObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        MessageSending::class => [
            RedirectDevEnvironmentEmails::class
        ],
        MessageSent::class => [
            LogSentMessage::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Workspace::observe(WorkspaceObserver::class);
        Alert::observe(AlertObserver::class);
        AlertMessage::observe(AlertMessageObserver::class);
        SupportMessage::observe(SupportMessageObserver::class);
        Indicator::observe(IndicatorObserver::class);
        AlertMessageAttachment::observe(AlertMessageAttachmentObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
