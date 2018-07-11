<?php


header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, Application, Method');
header('Access-Control-Allow-Credentials: true');



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['prefix'=> 'api','middleware'=>'auth'], function (){

    Route::get('/questions', 'QuestionController@getQuestions');
    Route::post('/answers', 'AnswerController@store');
    Route::post('/answer/{id}', 'AnswerController@update');
    Route::get('/answer/{id}', 'AnswerController@edit');
});


Route::group(['middleware'=> 'auth'], function (){

    Route::get('/', 'ExamController@index');
    Route::resource('exams', 'ExamController');

//    Route::get('/', function(){
//        return view('home');
//    });
});


Route::group(['middleware' => ['web', 'auth', 'admin'], 'prefix' => config('backpack.base.route_prefix')], function () {
    Route::auth();
    Route::get('logout', 'Auth\LoginController@logout');


    Route::group(['namespace'=>'Admin'], function (){

//        CRUD::resource('questioncategory', 'QuestionCategoryCrudController');
        CRUD::resource('question-paper', 'QuestionPaperCrudController');
        CRUD::resource('question', 'QuestionCrudController');
        CRUD::resource('examcategory', 'ExamCategoryCrudController');
        CRUD::resource('exam', 'ExamCrudController');
        CRUD::resource('leaderboards', 'LeaderboardCrudController');
        CRUD::resource('section-category', 'SectionCategoryCrudController');
        CRUD::resource('examtypes', 'ExamTypeCrudController');
        CRUD::resource('users', 'UserCrudController');
    });

    //    Route::get('login', 'Auth\ForgotPasswordController@logout');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/mail', function(){

//    return view('emails.exam_result')->with([ 'exam'=> \App\Models\Exam::find(12), 'user'=>Auth::user(), 'answer'=> Auth::user()->answers->first() ]);
//    dd(Auth::user()->answers->first()->id);
    return Mail::to('forgettingshyam@gmail.com')->send(new \App\Mail\ExamResult(Auth::user()->answers->first()));

    return true;
});


Route::get('/register', ['as'=>'register', function (){

    return redirect('/login')->withErrors(['email'=> 'Only students of Aptitude centre can signup! Please contact administrator at aptitudetvm@gmail.com']);
}]);

//Route::get('/test', function (){
//     $data1 = file_get_contents("http://api.smarterping.com/client/ping.aspx?apikey=ZTSP-YXIT-FIGM-1193");
//
//    dd(json_decode(explode("\r\n",$data1)[0], true ));
////    $data1 = json_decode($data1,true);
//});
