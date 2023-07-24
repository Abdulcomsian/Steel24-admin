<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LotLoserNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $lotId;
    public $loserName;

    /**
     * Create a new message instance.
     *
     * @param int $lotId
     * @param string $loserName
     * @return void
     */
    public function __construct($lotId, $loserName)
    {
        $this->lotId = $lotId;
        $this->loserName = $loserName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.emails.lot-loser-notification')
            ->subject('Lot Loser Notification')
            ->with([
                'lotId' => $this->lotId,
                'loserName' => $this->loserName,
            ]);
    }
}
