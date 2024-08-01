<?php

namespace Database\Seeders;

use App\Models\SupportMessage;
use App\Models\SupportMessageAttachment;
use Illuminate\Database\Seeder;

class SupportMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SupportMessage::create([
            'message' => 'I have a problem with my river, the color is green',
            'workspace_id' => '1',
            'support_id' => '1',
            'user_id' => '12',
        ]);

        SupportMessage::create([
            'message' => 'I have a problem with my account',
            'workspace_id' => '1',
            'support_id' => '1',
            'user_id' => '13',
        ]);

        SupportMessage::create([
            'message' => 'I have a problem with my account',
            'workspace_id' => '1',
            'support_id' => '1',
            'user_id' => '14',
        ]);

        SupportMessageAttachment::create([
            'support_message_id' => 1,
            'workspace_id' => 1,
            'file_name' => 'https://api.staging.azulfy.buzzvel.work/storage/images/logo.png',
        ]);
        
    }
}
