<?php

namespace App\Console\Commands;

use App\Http\Controllers\Administration\SpeednewsController;
use Illuminate\Console\Command;

class sendRecapSpeedNewsNoon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:recapSpeedNewsNoon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des recapitulatifs de speednews entre 06h et 12h.';

    protected $dt = '12:00';

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
        SpeednewsController::sendEmail($this->dt);
    }
}
