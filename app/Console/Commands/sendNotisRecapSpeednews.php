<?php

namespace App\Console\Commands;

use App\Http\Controllers\Administration\SpeednewsController;
use Illuminate\Console\Command;

class sendNotisRecapSpeednews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:recapSpeednews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recap des speednews';

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
