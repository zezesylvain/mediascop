<?php

namespace App\Console\Commands;

use App\Helpers\DbTablesHelper;
use App\Http\Controllers\Administration\SpeednewsController;
use App\Jobs\SendSpeedNews;
use App\Mail\Speednews;
use App\Models\User;
use App\Models\UserSpeednews;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendSpeednewsMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:speednewsMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des speednews aux utilisateurs';

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
     * @return mixed
     */
    public function handle()
    {
        SpeednewsController::sendEmail();
    }
}
