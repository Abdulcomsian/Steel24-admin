<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LotWinnerNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $winnerName;

    /**
     * Create a new message instance.
     *
     * @param string $winnerName The name of the winner bidder.
     * @return void
     */
    public function __construct($winnerName)
    {
        $this->winnerName = $winnerName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.emails.lot-winner-notification')
                    ->subject('Congratulations! You Won the Lot')
                    ->with([
                        'winnerName' => $this->winnerName,
                    ]);
    }
}
