<?php
/*
 *  ______     __  __     ______     ______     __   __   ______     __
 * /\  == \   /\ \/\ \   /\___  \   /\___  \   /\ \ / /  /\  ___\   /\ \
 * \ \  __<   \ \ \_\ \  \/_/  /__  \/_/  /__  \ \ \'/   \ \  __\   \ \ \____
 *  \ \_____\  \ \_____\   /\_____\   /\_____\  \ \__|    \ \_____\  \ \_____\
 *   \/_____/   \/_____/   \/_____/   \/_____/   \/_/      \/_____/   \/_____/
 *
 * Made By: Mauro Gama
 *
 * ♥ BY Buzzers: BUZZVEL.COM
 * Last Update: 2022.6.29
 */

namespace App\Listeners;

class RedirectDevEnvironmentEmails
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        if (app()->environment('dev')) {
            $to = $cc = $bcc = [];

            foreach ($event->message->getTo() as $toList) {
                $to[] = $toList->getAddress();
            }
            foreach ($event->message->getCc() as $toList) {
                $cc[] = $toList->getAddress();
            }
            foreach ($event->message->getBcc() as $toList) {
                $bcc[] = $toList->getAddress();
            }

            $event->message->to(config('settings.staging_catch_all_email'));

            $event->message->getHeaders()->addTextHeader(
                'X-Original-Emails',
                json_encode(compact('to', 'cc', 'bcc'), JSON_THROW_ON_ERROR)
            );
        }
    }
}
