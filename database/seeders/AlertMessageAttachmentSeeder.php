<?php

namespace Database\Seeders;

use App\Models\AlertMessage;
use App\Models\AlertMessageAttachment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlertMessageAttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alertMessages = AlertMessage::all();

        foreach ($alertMessages as $alertMessage) {
            $alertMessageId = $alertMessage->id;

            $attachments = AlertMessageAttachment::where('alert_message_id', $alertMessageId)->get();

            foreach ($attachments as $attachment) {
                DB::update('update alert_message_attachments set alert_message_id = ? where id = ?', [$alertMessageId, $attachment->id]);
            }
        }
    }
}
