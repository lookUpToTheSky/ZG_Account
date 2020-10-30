<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateuserListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_list', function(Blueprint $table)
        {
            // $table->increments('user_id');//主键自增
            // $table->string('user_name',60); //语言
            // $table->string('user_password', 25); //发布
            // $table->string('user_phong',11); //标题
            // $table->string('user_email', 60); //主要语言
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
