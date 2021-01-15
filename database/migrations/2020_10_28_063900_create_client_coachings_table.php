<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_trainings', function (Blueprint $table) {
            $table->id();
            $table->integer('day');
            $table->timestamp('date');
            $table->string('browse_file')->nullable();
            $table->enum('is_attended', ['0', '1'])->default('0')->comment('0: No, 1: Yes');
            $table->bigInteger('training_id')->unsigned();
            $table->foreign('training_id')->references('id')->on('trainings')->onDelete('cascade');
            $table->bigInteger('client_training_info_id')->unsigned();
            $table->foreign('client_training_info_id')->references('id')->on('client_training_infos')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned();
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
        Schema::dropIfExists('client_trainings');
    }
}
