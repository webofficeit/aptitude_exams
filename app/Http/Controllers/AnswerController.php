<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Exam;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        var_dump($request->all());
//        $data = $request->all();
//        $data['start_date_time'] = Carbon::now();
        $answer =  Answer::firstOrCreate(['exam_id'=> $request->get('exam_id'), 'user_id' => $request->get('user_id') ]);
        $answer->start_date_time = Carbon::now();
        $answer->save();
        return $answer;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        foreach (Answer::all() as $answer){

            if($answer->answers and !$answer->sectional_marks){
                $sectionAnswers = [];
                $exam = $answer->exam;
                foreach (json_decode($answer->answers) as $index=>$ans){
                    if($ans and isset($ans->question_id)){
                        $question = Question::find($ans->question_id);

                        if(isset($question->section_category_id)){
                            $mark = 0;
                            if($ans->type== 'answered' and $ans->optionIndex== $ans->optionIndexOfAnswer)
                                $mark= $exam->marks_per_question;
                            else if($ans->type== 'answered' and $ans->optionIndex !== $ans->optionIndexOfAnswer and $exam->negative_marks)
                                $mark= - $exam->negative_marks;

                            $sectionAnswers[$index] = ['section_category_id'=> $question->section_category_id, 'mark'=> $mark];
                        }
                    }
                }

                $marksPerSection = [];

                foreach ($sectionAnswers as $ans){
                    if(!isset($marksPerSection[$ans['section_category_id']]))
                        $marksPerSection[$ans['section_category_id']] = 0;

                    $marksPerSection[$ans['section_category_id']] += $ans['mark'];
                }

                $sectionalMarks = [];$i=0;
                foreach ($marksPerSection as $key=>$mark){
                    $sectionalMarks[$i] = ['section_category_id'=> $key, 'marks'=> $mark];
                    $i++;
                }
                $answer->sectional_marks = json_encode($sectionalMarks);
                $answer->save();
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $answer = Answer::find($id);
//        var_dump($request->all());
//        return $answer;
        $answer->update($request->all());

        if(!$answer->first_attempt_marks){
            $answer->first_attempt_marks = $request['total_marks'];

            // Lets update the section answers
            if($answer->answers){
                $sectionAnswers = [];
                $exam = $answer->exam;
                foreach (json_decode($answer->answers) as $index=>$ans){
                    if($ans and isset($ans->question_id)){
                        $question = Question::find($ans->question_id);

                        if(isset($question->section_category_id)){
                            $mark = 0;
                            if($ans->type== 'answered' and $ans->optionIndex== $ans->optionIndexOfAnswer)
                                $mark= $exam->marks_per_question;
                            else if($ans->type== 'answered' and $ans->optionIndex !== $ans->optionIndexOfAnswer and $exam->negative_marks)
                                $mark= - $exam->negative_marks;

                            $sectionAnswers[$index] = ['section_category_id'=> $question->section_category_id, 'mark'=> $mark];
                        }
                    }
                }

                $marksPerSection = [];

                foreach ($sectionAnswers as $ans){
                    if(!isset($marksPerSection[$ans['section_category_id']]))
                        $marksPerSection[$ans['section_category_id']] = 0;

                    $marksPerSection[$ans['section_category_id']] += $ans['mark'];
                }

                $sectionalMarks = [];$i=0;
                foreach ($marksPerSection as $key=>$mark){
                    $sectionalMarks[$i] = ['section_category_id'=> $key, 'marks'=> $mark];
                    $i++;
                }
                $answer->sectional_marks = json_encode($sectionalMarks);
                $answer->save();
            }

        }
        $answer->save();

        if(Auth::user()){
            if($answer->finished){
                if(Auth::user()->mobile)
                    Auth::user()->sendSms('Your score for Aptitude\'s '.$answer->exam->name.' exam is '.$answer->total_marks.'. You gave '.$answer->correct_answers.' correct answers,  and '.$answer->wrong_answers.' incorrect answers.', [Auth::user()->mobile]);
//                    file_get_contents('http://alert.smsspot.in/httpapi/smsapi?uname=aptitude&password=password123&sender=APTTVM&receiver='.Auth::user()->mobile.'&route=T&msgtype=1&sms='.urlencode());

                \Mail::to(Auth::user()->email)->send(new \App\Mail\ExamResult($answer));
            }

            Auth::logout();

        }

        return $answer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
