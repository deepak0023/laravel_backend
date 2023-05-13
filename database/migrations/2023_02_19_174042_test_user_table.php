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
        // Illuminate\Support\Facades\DB::setDefaultConnection('sqlite');

        if(!Schema::connection('sqlite')->hasTable('users')) {
            Schema::connection('sqlite')->create('users', function (Blueprint $table) {
                $table->id();
                $table->integer('user_rl_id')->unsigned()->default('2'); // setting to normal user role
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            });

            DB::connection('sqlite')->unprepared('
                CREATE TRIGGER users_updated_at_trigger
                AFTER UPDATE ON users
                FOR EACH ROW
                BEGIN
                    UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = OLD.id;
                END
            ');
        }

        // Illuminate\Support\Facades\DB::setDefaultConnection('mysql');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('sqlite')->disableForeignKeyConstraints();
        // Illuminate\Support\Facades\DB::setDefaultConnection('sqlite');
        DB::connection('sqlite')->unprepared('DROP TRIGGER IF EXISTS users_updated_at_trigger');
        Schema::connection('sqlite')->dropIfExists('users');
        // Illuminate\Support\Facades\DB::setDefaultConnection('mysql');
        Schema::connection('sqlite')->enableForeignKeyConstraints();
    }
};
