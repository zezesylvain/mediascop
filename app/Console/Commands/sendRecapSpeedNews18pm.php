<?php

namespace App\Console\Commands;

use App\Http\Controllers\Administration\SpeednewsController;
use Illuminate\Console\Command;

class sendRecapSpeedNews18pm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:recapSpeedNews06pm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des récapitulatifs de speednews de 6h à 18h.';

    protected $dt = '18:00';
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
