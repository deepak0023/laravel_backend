<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('user_rl_id')->unsigned()->default('2')->nullable(false)->after('id'); // setting to normal user role
                $table->foreign('user_rl_id')->references('rl_id')->on('roles');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign('users_user_rl_id_foreign');
                $table->dropColumn('user_rl_id');
            });
        }
    }
};
