<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('title');
            $table->unsignedBigInteger('correct_ans')->nullable();
            $table->unsignedInteger('mark');
            $table->unsignedBigInteger('exam_id'); 
            $table->timestamps(); 

            $table->foreign('exam_id')->references('id')->on('exam')->onDelete('cascade');
            //$table->foreign('correct_ans')->references('id')->on('question_option')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question');
    }
}
