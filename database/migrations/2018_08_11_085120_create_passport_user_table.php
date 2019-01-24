<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassportUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passport_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 20)->nullable()->commet('用户名');
            $table->string('nickname', 30)->nullable()->comment('昵称');
            $table->string('password', 150)->commet('密码');
            $table->string('email', 50)->nullable()->commet('email');
            $table->string('mobile', 15)->nullable()->commet('手机号');
            $table->unsignedTinyInteger('status')->default(0)->commet('状态，1正常，2未激活，3禁止');
            $table->unsignedBigInteger('created_at')->default(0)->comment('创建时间');
            $table->unsignedBigInteger('updated_at')->default(0)->comment('修改时间');
            $table->unsignedBigInteger('deleted_at')->default(null)->nullable()->comment('删除时间');
            $table->ipAddress('register_ip')->nullable()->commnet('Register IpAddress');

            $table->unique('name');
            $table->unique('mobile');
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
        Schema::dropIfExists('passport_users');
    }
}
