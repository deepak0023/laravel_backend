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
        if(!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->increments('rl_id');
                $table->string('rl_name');
                $table->timestamp('rl_created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('rl_updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if(!Schema::connection('sqlite')->hasTable('roles')) {
            Schema::connection('sqlite')->create('roles', function (Blueprint $table) {
                $table->increments('rl_id');
                $table->string('rl_name');
                $table->timestamp('rl_created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('rl_updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            });

            DB::connection('sqlite')->unprepared('
                CREATE TRIGGER roles_updated_at_trigger
                AFTER UPDATE ON roles
                FOR EACH ROW
                BEGIN
                    UPDATE roles SET updated_at = CURRENT_TIMESTAMP WHERE rl_id = OLD.rl_id;
                END
            ');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
        DB::connection('sqlite')->unprepared('DROP TRIGGER IF EXISTS  roles_updated_at_trigger');
        Schema::connection('sqlite')->dropIfExists('roles');
    }
};
