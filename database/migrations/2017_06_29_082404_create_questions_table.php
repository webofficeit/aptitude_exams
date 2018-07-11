<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('instructions')->nullable();
            $table->text('question_summary')->nullable();
            $table->text('question');
//            $table->text('sub_questions')->nullable();
            $table->text('options')->nullable();
//            $table->unsignedInteger('answer_option')->nullable();
            $table->unsignedInteger('section_category_id')->index();
            $table->unsignedInteger('question_paper_id')->nullable()->index();
//            $table->unsignedInteger('exam_id')->nullable();  // if the questions were not entered individually, but rather as part of a question paper
//            $table->unsignedInteger('question_id')->nullable(); // if the entry is sub question of a  "question-with-instructions"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
