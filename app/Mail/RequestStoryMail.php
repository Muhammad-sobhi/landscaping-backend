<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestStoryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $url;

    /**
     * Create a new message instance.
     */
    public function __construct($lead, $token)
    {
        $this->lead = $lead;
        
        // This ensures the link always points to your Next.js Landing Page (Port 3000)
        $this->url = config('app.landing_page_url') . "/share-story?token=" . $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We would love to hear your story!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.request_story', // Make sure this matches your file in resources/views/emails/
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}