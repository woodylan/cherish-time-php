<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cherish_time.tb_time', function (Blueprint $table) {
            $table->string('id', 32);
            $table->string('type')->comment('类型');
            $table->string('user_id')->comment('用户ID');
            $table->string('color', 10)->comment('颜色');
            $table->string('name')->comment('名称');
            $table->unsignedInteger('date')->comment('日期');
            $table->string('remark')->comment('备注');

            $table->string('create_user_id', 32);
            $table->string('update_user_id', 32);

            $table->unsignedInteger('create_time');
            $table->unsignedInteger('update_time');
            $table->unsignedInteger('deleted_at')->nullable();

            $table->primary('id');
            $table->index('user_id');
            $table->index('date');
            $table->index('type');
            $table->index('create_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cherish_time.tb_time');
    }
}
