<?php

namespace Database\Seeders;

use App\Models\AlertMessage;
use App\Models\AlertMessageAttachment;
use Illuminate\Database\Seeder;

class AlertMessageSeeder extends Seeder
{
    public function run(): void
    {
        AlertMessage::create([
            'user_id' => 1,
            'alert_id' => 1,
            'workspace_id' => 1,
            'message' => 'Hello',
        ]);

        AlertMessage::create([
            'user_id' => 2,
            'alert_id' => 1,
            'workspace_id' => 1,
            'message' => 'Hello Oeiras, how are you?',
        ]);

        AlertMessageAttachment::create([
            'alert_message_id' => 1,
            'workspace_id' => 1,
            'file_name' => 'https://api.staging.azulfy.buzzvel.work/storage/images/logo.png',
            'file_type' => 'image',
        ]);

        AlertMessageAttachment::create([
            'alert_message_id' => 1,
            'workspace_id' => 1,
            'file_name' => 'https://api.staging.azulfy.buzzvel.work/storage/images/logo.png',
            'file_type' => 'image',
        ]);

        AlertMessageAttachment::create([
            'alert_message_id' => 1,
            'workspace_id' => 1,
            'file_name' => 'https://api.staging.azulfy.buzzvel.work/storage/images/logo.png',
            'file_type' => 'image',
        ]);

        AlertMessageAttachment::create([
            'alert_message_id' => 1,
            'workspace_id' => 1,
            'file_name' => 'https://api.staging.azulfy.buzzvel.work/storage/images/logo.png',
            'file_type' => 'image',
        ]);
  
    }
}


