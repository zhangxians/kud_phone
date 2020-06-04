<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbIpTable extends Migration
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
        Schema::create('tb_ip', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip')->comment('ip')->nullable();
            $table->string('status')->comment('状态 0 正常')->default(0);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_ip');
    }
}
