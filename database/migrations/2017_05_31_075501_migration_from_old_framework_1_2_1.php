<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationFromOldFramework121 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //laravel naming conventions
        Schema::rename("Comment", "comments");
        Schema::rename("Content", "contents");
        Schema::rename("Hashtags", "hashtags");
        Schema::rename("Score", "scores");
        Schema::rename("Notification", "notifications");
        Schema::rename("User", "users");


        Schema::table('contents', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');

        });

        Schema::table('comments', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('content_id')->references('id')->on('contents');

        });

        Schema::table('hashtags', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->timestamps();
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->foreign('from_user_id')->references('id')->on('users');
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
