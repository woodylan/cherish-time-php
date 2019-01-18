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
            $table->string('color', 64)->comment('颜色');
            $table->string('name')->comment('名称');
            $table->unsignedInteger('date')->comment('日期');
            $table->string('remark')->comment('备注');

            $table->string('create_user_id', 32);
            $table->string('update_user_id', 32);

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->primary('id');
            $table->index('user_id');
            $table->index('date');
            $table->index('type');
            $table->index('created_at');
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
