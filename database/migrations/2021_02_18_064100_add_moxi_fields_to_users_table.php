<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoxiFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('pancreas_function')->nullable()->after('weight_unit');
            $table->integer('liver_congestion')->nullable()->after('pancreas_function');
            $table->integer('adrenal_function')->nullable()->after('liver_congestion');
            $table->integer('gut_function')->nullable()->after('adrenal_function');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pancreas_function');
            $table->dropColumn('liver_congestion');
            $table->dropColumn('adrenal_function');
            $table->dropColumn('gut_function');
        });
    }
}
