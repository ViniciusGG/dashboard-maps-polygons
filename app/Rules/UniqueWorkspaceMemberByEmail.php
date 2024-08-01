<?php

namespace App\Rules;

use App\Models\User;
use App\Models\WorkspaceMember;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueWorkspaceMemberByEmail implements ValidationRule
{
    public function __construct(protected $email)
    {
        $this->email = $email;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = config('app.key') . $value;
        $emailDecrypt = base64_encode($email);
        $user = User::where('email', $emailDecrypt)->first();
        if(!$user) {
            return;
        }
        $workspaceMember = WorkspaceMember::where('user_id', $user->id)->first();
        if ($workspaceMember) {
            $fail(__('workspace.already_on_workspace'));
        }
    }
}
