<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSpeednews extends Mailable
{
    use Queueable, SerializesModels;

    public $donnees = [];

    public $subject = 'Speednews Mediascop';

    public $type = 1;

    public $laDateDuJour = '';


    /**
     * Create a new message instance.
     *
     * @param array $donnees
     * @param string $laDateDuJour
     * @param int $type
     */
    public function __construct(array $donnees, string $laDateDuJour, int $type)
    {
        $this->donnees = $donnees;
        $this->type = $type;
        $this->laDateDuJour = $laDateDuJour;
        $this->subject = $type === 1 ? $donnees['operation'] : 'Recap Speednews';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       // dd($this->donnees);
        $view = $this->type === 1 ? 'mail' : 'recapMail';
        return $this->view("Email.Speednews.$view");
    }
}
