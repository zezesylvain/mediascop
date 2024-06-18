<?php

namespace App\Console\Commands;

use App\Http\Controllers\Administration\SpeednewsController;
use Illuminate\Console\Command;

class sendRecapSpeedNews06am extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:recapSpeedNews06am';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des recapitulatifs de speednews de la veille.';

    protected $dt = '06:00';

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
