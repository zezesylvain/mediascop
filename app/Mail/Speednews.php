<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Speednews extends Mailable
{
    use Queueable, SerializesModels;

    protected $msg = [] ;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $msg)
    {
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $msg = $this->msg;
        if (!array_key_exists('fichiers',$msg)):
            return $this->view('Email.Speednews.mail',compact('msg'));
        else:
            return $this->view('Email.Speednews.mail',compact('msg'))->attach($msg['fichiers']['docCampagne']);
        endif;
    }
}
