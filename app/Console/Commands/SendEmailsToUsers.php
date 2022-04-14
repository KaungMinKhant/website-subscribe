<?php

namespace App\Console\Commands;

use App\Notifications\SendEmailNotification;
use App\Models\WebUser;
use Illuminate\Console\Command;

class SendEmailsToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:send_emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails about post updates to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = WebUser::all();
        foreach ($users as $user)
        {
            $user->notify(new SendEmailNotification());
        }
    }
}
