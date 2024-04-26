<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\MailableName;
use Illuminate\Support\Facades\Mail;


class SendEmailForFiftyLikes extends Command
{
    protected $signature = 'email:fiftyLikes';
    protected $description = 'Send an email to users who reached 50 likes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::where('likes', 50)->whereNull('notified_for_sixty_likes')->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new MailableName($user));
            $user->notified_for_sixty_likes = now(); // Ensure a timestamp or flag is set to avoid duplicate emails
            $user->save();
        }

        $this->info('Emails sent successfully to users with 50 likes.');
    } 
}
