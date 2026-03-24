<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TodayEventsMail extends Mailable
{
    use SerializesModels;

    public $events;
    public $user;

    public function __construct($user, $events)
    {
        $this->user = $user;
        $this->events = $events;
    }

    public function build()
    {
        return $this->subject("Today's Events")
            ->view('emails.today-events');
    }
}