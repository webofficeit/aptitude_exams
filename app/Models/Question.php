<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Question extends Model
{
    use CrudTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    //protected $table = 'questions';
    //protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
     protected $fillable = ['instructions', 'instructions_image', 'question','options','answer_option','question_category_id','exam_id','question_id','sub_questions','question_paper_id', 'section_category_id','question_summary'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $fakeColumns = ['options'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getOptionsAttribute($value)
    {
        return (string)($value);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function sectionCategory(){
        return $this->belongsTo('App\Models\SectionCategory');
    }

    public function questionPaper(){
        return $this->belongsTo('App\Models\QuestionPaper');
    }

//    public function parentQuestion(){
//        return $this->belongsTo('App\Models\Question', 'question_id');
//    }

//    public function exam(){
//        return $this->belongsTo('App\Models\Exam');
//    }

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
