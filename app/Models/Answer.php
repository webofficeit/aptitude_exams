<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    protected $fillable = ['exam_id', 'user_id', 'answers', 'total_marks', 'start_date_time', 'finished', 'correct_answers', 'wrong_answers', 'exam_summary_html', 'sectional_marks'];


    public function exam(){
        return $this->belongsTo(Exam::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
