<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name',40)->commet('用户名');
            $table->string('password',150)->commet('密码');
            $table->string('email',50)->nullable()->commet('email');
            $table->string('tel',15)->nullable()->commet('手机号');
            $table->unsignedInteger('status')->default(0)->commet('状态，1正常，2未激活，3禁止');
            $table->unsignedBigInteger('created_at')->default(0)->comment('创建时间');
            $table->unsignedBigInteger('updated_at')->default(0)->comment('修改时间');
            $table->unsignedBigInteger('updated_at')->default(0)->comment('修改时间');
            $table->unsignedBigInteger('deleted_at')->default(null)->nullable()->comment('删除时间');
            $table->string('ticket', 150)->nullable()->comment('token');
            $table->string('remember_token', 150)->nullable()->comment('remember token');
            $table->ipAddress('register_ip')->nullable()->commnet('Register IpAddress');
            $table->unsignedInteger('ticket_expired_at')->default(0)->comment('ticket过期时间');

            $table->unique('name');
            $table->unique('tel');
            $table->unique('email');
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
