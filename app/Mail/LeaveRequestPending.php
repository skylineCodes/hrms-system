<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeaveRequestPending extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $leave;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($leave)
    {
        $this->leave = $leave;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('ADMIN_MAIL'))
                    ->subject('Leave Request Pended')
                    ->view('emails/leaverequestpending')
                    ->with('leave', $this->leave);
    }
}
