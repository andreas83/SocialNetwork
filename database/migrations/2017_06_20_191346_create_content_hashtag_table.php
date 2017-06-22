<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentHashtagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_hashtag', function(Blueprint $table)
        {
            $table->integer('content_id')->unsigned()->nullable();
            $table->foreign('content_id')->references('id')
                ->on('contents')->onDelete('RESTRICT');

            $table->integer('hashtag_id')->unsigned()->nullable();
            $table->foreign('hashtag_id')->references('id')
                  ->on('hashtags')->onDelete('RESTRICT');

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
        //
    }
}
