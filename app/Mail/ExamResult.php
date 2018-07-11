<?php

namespace App\Mail;

use App\Models\Answer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;

class ExamResult extends Mailable
{
    use Queueable, SerializesModels;

    protected $answer;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Answer $answer)
    {
        //
        $this->answer = $answer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.exam_result')->with(['answer'=> $this->answer, 'user'=> $this->answer->user, 'exam'=> $this->answer->exam ]);
    }
}
