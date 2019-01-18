<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cherish_time.tb_user', function (Blueprint $table) {
            $table->string('id', 32);
            $table->string('open_id')->comment('open_id');
            $table->string('nick_name')->comment('昵称');
            $table->unsignedTinyInteger('sex')->comment('性别');
            $table->string('city', 32)->comment('城市');
            $table->string('province', 32)->comment('省份');
            $table->string('country', 32)->comment('国家');
            $table->string('avatar')->comment('头像');

            $table->string('create_user_id', 32);
            $table->string('update_user_id', 32);

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->primary('id');
            $table->index('open_id');
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
        Schema::dropIfExists('cherish_time.tb_user');
    }
}
