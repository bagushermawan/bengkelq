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
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('username')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('alamat');
            $table->date('tgl_lahir');
            // $table->unsignedBigInteger('role_id');
            $table->rememberToken();
            $table->timestamps();

            $table->foreignId('role_id');
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
