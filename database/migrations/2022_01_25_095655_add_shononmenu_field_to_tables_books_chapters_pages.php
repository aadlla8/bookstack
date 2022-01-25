<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShononmenuFieldToTablesBooksChaptersPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->boolean('showonmenu')->nullable();
        });
        Schema::table('chapters', function (Blueprint $table) {
            $table->boolean('showonmenu')->nullable();
        });
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('showonmenu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('showonmenu');
        });
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn('showonmenu');
        });
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('showonmenu');
        });
    }
}
