<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ExamType extends Model
{
    use CrudTrait;

    protected $name= 'exam_types';
    protected $fillable= ['name'];

    public function exams(){
        return $this->belongsToMany(Exam::class,'exam_type_exam');
    }
}
