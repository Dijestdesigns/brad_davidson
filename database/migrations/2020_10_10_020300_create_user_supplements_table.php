<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSupplementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_supplements', function (Blueprint $table) {
            $table->id();
            $table->integer('row_id');
            $table->date('date');
            $table->text('supplement')->nullable();
            $table->text('upon_waking')->nullable();
            $table->text('at_breakfast')->nullable();
            $table->text('at_lunch')->nullable();
            $table->text('at_dinner')->nullable();
            $table->text('before_bed')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('user_supplements');
    }
}
