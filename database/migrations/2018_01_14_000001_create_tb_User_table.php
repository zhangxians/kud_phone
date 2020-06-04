<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 账户表
         */
        Schema::create('tb_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->comment('名称');
            $table->string('password')->comment('密码');
            $table->string('ip')->comment('最后登录ip')->nullable();
            $table->integer('status')->comment('状态 0 正常')->default(0);
            $table->string('desc',500)->comment('描述')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_user');
    }
}
