<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCoursesStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses_student', function (Blueprint $table) {
            $table->integer('total_mark')->default(0);
            $table->longText('fail_questions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses_student', function (Blueprint $table) {
            //
            $table->dropColumn('total_mark');
            $table->dropColumn('fail_questions');
        });
    }
}
