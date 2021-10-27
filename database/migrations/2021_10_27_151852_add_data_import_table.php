<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataImportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_import', function (Blueprint $table) {
            //
            $table->integerIncrements('id');
            $table->string('page_title')->nullable();
            $table->string('page_content')->nullable();
            $table->string('chapter')->nullable();
            $table->string('book')->nullable();
            $table->string('shelf')->nullable();
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
        Schema::dropIfExists('data_import');
    }
}
