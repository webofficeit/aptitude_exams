<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Exam extends Model
{
    use CrudTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    //protected $table = 'exams';
    //protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
     protected $fillable = ['name','accept_instructions','start_date_time', 'end_date_time', 'duration','no_of_questions','max_entries','exam_category_id','sms_notifications','display_answer_key','questions','marks_per_question','negative_marks','question_paper_id', 'exam_type_id', 'cut_off_marks'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function examCategory(){
        return $this->belongsTo(ExamCategory::class,'exam_category_id');
    }

    public function questionPaper(){
        return $this->belongsTo(QuestionPaper::class,'question_paper_id');
    }

    public function examTypes(){
        return $this->belongsToMany(ExamType::class,'exam_type_exam');
    }

    public function answers(){
        return $this->hasMany(Answer::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
