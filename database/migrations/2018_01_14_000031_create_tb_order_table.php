<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 订单表
         */

        Schema::create('tb_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('名称');
            $table->string('phone1')->comment('phone')->nullable();
            $table->string('phone2')->comment('电话')->nullable();
            $table->string('phone3')->comment('电话')->nullable();
            $table->string('phone4')->comment('电话4')->nullable();
            $table->string('phone5')->comment('电话5')->nullable();
            $table->string('address')->comment('地址')->nullable();
            $table->string('package')->comment('套餐')->nullable();
            $table->string('ip')->comment('操作者ip')->nullable();
            $table->integer('user_id')->comment('操作者')->nullable();
            $table->integer('type')->comment('type 0：未处理，1空号，2：未接听，3：无意愿，4：有意愿，再联系，5：意愿强烈 ,6成功办理 7其他')->default(0);
            $table->string('desc',500)->comment('描述')->nullable();
            $table->timestamps();
            $table->softDeletes();

            //索引
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_order');
    }
}
