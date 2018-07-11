<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->dateTime('start_date_time')->nullable();
            $table->dateTime('end_date_time')->nullable();
            $table->unsignedInteger('duration')->default(10)->nullable();
            $table->unsignedInteger('marks_per_question');
            $table->float('negative_marks')->nullable();
            $table->unsignedInteger('max_entries')->nullable();
            $table->unsignedInteger('shuffling_count')->nullable();
            $table->unsignedInteger('exam_category_id')->references('id')->on('exam_categories');
            $table->unsignedInteger('question_paper_id')->references('id')->on('question_papers');
            $table->text('questions')->nullable();
            $table->boolean('sms_notifications')->default(false)->nullable();
            $table->boolean('display_answer_key')->default(true)->nullable();
            $table->boolean('accept_instructions')->default(false)->nullable();
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
        Schema::dropIfExists('exams');

    }
}
