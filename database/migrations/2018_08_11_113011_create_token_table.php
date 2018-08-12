<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passport_tokens', function (Blueprint $table) {
            $table->string('token',50);
            $table->unsignedBigInteger('user_id')->default(0)->comment('用户ID');
            $table->jsonb('applications')->comment('所属应用');
            $table->unsignedBigInteger('expired_at')->default(0)->comment('过期时间');
            $table->primary('token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passport_tokens');
    }
}
