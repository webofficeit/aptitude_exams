<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamType;
use App\Models\SectionCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $upcomingExams = !Auth::user()->examType? null : Auth::user()->examType->exams()->where('exam_type_id', Auth::user()->exam_type_id )->where( 'start_date_time', '>', Carbon::now())->where('start_date_time', '<', Carbon::today()->addDays(30))->get();

        $liveExams = !Auth::user()->examType? null : Auth::user()->examType->exams()->where('exam_type_id', Auth::user()->exam_type_id )->where('start_date_time', '<', Carbon::now())->where('end_date_time', '>', Carbon::now() )->get();

        return view('exams.index', compact('upcomingExams', 'liveExams'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {

        $now = date('Y-m-d G:II');
//        dd($now);
        $instructions = $exam->questionPaper->instructions;
        $sections = SectionCategory::all();

        $exam->load(['examCategory','questionPaper'=> function($query){

            return $query->select('id','name','questions', 'answer_key_url', 'created_at');
        }]);

        return view('exams.show', compact('exam','now','instructions','sections'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
