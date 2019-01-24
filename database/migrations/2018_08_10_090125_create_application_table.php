<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passport_applications', function (Blueprint $table) {
            $table->char('app_key', 10)->comment('应用KEY');
            $table->char('app_secret', 40)->default('')->comment('应用密匙');
            $table->unsignedTinyInteger('status')->default(0)->commet('状态，1正常，2禁止');
            $table->unsignedBigInteger('created_at')->default(0)->comment('创建时间');
            $table->unsignedBigInteger('updated_at')->default(0)->comment('修改时间');
            $table->unsignedBigInteger('deleted_at')->default(null)->nullable()->comment('删除时间');
            
            $table->primary('app_key');
            $table->unique('app_secret');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passport_applications');
    }
}
