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
        if(Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->unsignedBigInteger('cm_user_id')->unsigned()->nullable(false)->after('cm_id');
                $table->foreign('cm_user_id')->references('id')->on('users');
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
        if(Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropForeign('comments_cm_user_id_foreign');
                $table->dropColumn('cm_user_id');
            });
        }
    }
};
