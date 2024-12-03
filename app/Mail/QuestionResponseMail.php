<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuestionResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $response;
    public $question;

    /**
     * Create a new message instance.
     */
    public function __construct($response, $question)
    {
        $this->response = $response;
        $this->question = $question;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.question_response')
            ->with([
                'response' => $this->response,
                'question' => $this->question,
            ])
            ->replyTo('no-reply@example.com')
            ->subject('Response to Your Question on FakeBook');
    }
}
