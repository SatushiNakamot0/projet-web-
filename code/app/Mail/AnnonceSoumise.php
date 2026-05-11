<?php

namespace App\Mail;

use App\Models\Annonce;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnonceSoumise extends Mailable
{
    use Queueable, SerializesModels;

    public $annonce;

    public function __construct(Annonce $annonce)
    {
        $this->annonce = $annonce;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de publication - Votre annonce est en attente',
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: '
                <h1>Merci pour votre annonce !</h1>
                <p>Votre annonce <strong>' . $this->annonce->titre . '</strong> a bien été soumise.</p>
                <p>Elle est actuellement en attente de modération. Vous recevrez un email dès qu\'elle sera publiée.</p>
                <br>
                <p>L\'équipe de modération.</p>
            ',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
