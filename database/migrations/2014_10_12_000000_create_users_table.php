<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('contact')->nullable();
            $table->enum('category', [0, 1, 2, 3, 4])->default(false)->comment('0: None, 1: Phase1, 2: Phase2, 3: Phase3, 4: Monthly Breakthrough');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // $table->text('notes')->nullable();
            $table->string('profile_photo')->nullable();
            $table->text('shipping_address')->nullable();
            $table->enum('gender', ['n', 'm', 'f'])->comment('n: None, m: Malem f:Female')->default('n');
            $table->integer('age')->nullable();
            $table->integer('weight')->nullable();
            $table->enum('weight_unit', ['n', 'k', 'p'])->comment('n: None, k: KG, p: Pound')->default('n');
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_superadmin')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
