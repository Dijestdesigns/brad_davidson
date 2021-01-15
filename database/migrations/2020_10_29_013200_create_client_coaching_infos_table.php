<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientTrainingInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_training_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('total_days');
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->enum('is_done', ['0', '1'])->default('0')->comment('0: No, 1: Yes');
            $table->text('training_ids')->nullable();
            /*$table->bigInteger('training_id')->unsigned();
            $table->foreign('training_id')->references('id')->on('trainings')->onDelete('cascade');*/
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
        Schema::dropIfExists('client_training_infos');
    }
}
