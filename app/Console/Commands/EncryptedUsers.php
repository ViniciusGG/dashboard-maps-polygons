<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class EncryptedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encrypted-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypt user email and phone number.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $this->info('Encrypting user email and phone for user: ' . $user->id);
            $emailDecrypt = Crypt::decryptString($user->email);
            $phoneDecrypt = ($user->phone) ? Crypt::decryptString($user->phone) : null;
            $email = config('app.key') . $emailDecrypt;
            $phone = config('app.key') . $phoneDecrypt;
            User::where('id', $user->id)->update([
                'email' => base64_encode($email),
                'phone' => ($phoneDecrypt) ? base64_encode($phone) : null,
            ]);
        }
    }
}
