<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to(array_map('trim', explode(',', $this->data['to'])));

        $this->subject($this->data['subject'])
                ->view('emails.form_submitted')
                ->with(['body' => $this->data['body']]);

        if (!empty($this->data['cc'])) {
            $this->cc(array_map('trim', explode(',', $this->data['cc'])));
        }

        if (!empty($this->data['bcc'])) {
            $this->bcc(array_map('trim', explode(',', $this->data['bcc'])));
        }

        if (!empty($this->data['attachment'])) {
            foreach ($this->data['attachment'] as $key => $value) {
                $name = explode('_', $value, 2);
                $this->attach(public_path('uploads').'/'.config('constants.doc_path').'/'.$value, [
                    'as' => $name[1],
                ]);
            }
        }

        if (!empty($this->data['pdf_attachment']) && !empty($this->data['pdf_name'])) {

            $this->attachData($this->data['pdf_attachment']->Output(), $this->data['pdf_name'], [
                'mime' => 'application/pdf',
            ]);
        }
        
        return $this;
    }
}
