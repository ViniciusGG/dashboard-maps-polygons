<?php
namespace App\Listeners;

use App\Repositories\EmailLogRepository;

class LogSentMessage
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
     */
    public function handle(object $event): void
    {
        $to = [];
        $from = [];

        foreach ($event->message->getTo() as $toList) {
            $to[] = $toList->getAddress();
        }

        foreach ($event->message->getFrom() as $fromList) {
            $from[] = $fromList->getAddress();
        }
        $subject = $event->message->getSubject();
        $message = $event->message->getBody();

        $message = $this->parseBodyText($event->message->getTextBody());
        $repoEmailLog = new EmailLogRepository();
        $repoEmailLog->store(json_encode($to), json_encode($from), $subject, json_encode($message), json_encode($event));
    }

    private function parseBodyText($body): string
    {
        return preg_replace('~[\r\n]+~', '<br>', $body);
    }
}
