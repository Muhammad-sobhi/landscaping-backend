<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProfessionalQuote extends Mailable
{
    use Queueable, SerializesModels;

    public $offer;
    public $settings;

    public function __construct($offer, $settings)
    {
        $this->offer = $offer;
        $this->settings = $settings;
    }

    public function build()
{
    // Fix: use firstWhere on the collection
    $nameSetting = $this->settings->firstWhere('key', 'project_name');
    $appName = $nameSetting ? $nameSetting->value : config('app.name');

    return $this->subject("Quote #QT-{$this->offer->id} from {$appName}")
                ->view('emails.quote');
}
}