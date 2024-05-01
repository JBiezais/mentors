<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use src\Domain\Mail\Models\Mail;
use src\Domain\Student\Models\Student;

class OAuthTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates oAuth token';


    public function handle(): int
    {
        app('oauth.service')->getToken();

        return Command::SUCCESS;
    }
}
