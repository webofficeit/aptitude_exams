<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomFieldsToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->unsignedInteger('exam_type_id');
        });

//        Schema::table('exams', function (Blueprint $table) {
//           $table->unsignedInteger('exam_type_id');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('exam_type_id');
        });
//        Schema::table('exams', function (Blueprint $table) {
//            $table->dropColumn('exam_type_id');
//        });
    }
}
