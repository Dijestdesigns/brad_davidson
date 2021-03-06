<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->text('message')->nullable();
            $table->string('file')->nullable();
            $table->enum('is_individual', ['0', '1'])->default('0')->comment('0: Nope, 1: Yes');
            $table->bigInteger('chat_room_user_id')->unsigned()->nullable();
            $table->foreign('chat_room_user_id')->references('id')->on('chat_room_users')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('send_by')->unsigned()->nullable();
            $table->foreign('send_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('chats');
    }
}
