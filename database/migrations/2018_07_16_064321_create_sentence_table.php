<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSentenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cherish_time.tb_sentence', function (Blueprint $table) {
            $table->string('id', 32);
            $table->string('content')->comment('短句');
            $table->string('author', 32)->comment('作者');
            $table->string('book', 32)->comment('书名');
            $table->unsignedInteger('show_times')->comment('展示次数');

            $table->string('create_user_id', 32);
            $table->string('update_user_id', 32);

            $table->unsignedInteger('create_time');
            $table->unsignedInteger('update_time');
            $table->unsignedInteger('deleted_at')->nullable();

            $table->primary('id');
            $table->index('show_times');
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
        Schema::dropIfExists('cherish_time.tb_sentence');
    }
}
