<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('read_user');
            $table->foreign('read_user')->references('id')->on('users')->onDelete('cascade');;
            $table->unsignedBigInteger('read_book');
            $table->foreign('read_book')->references('id')->on('books')->onDelete('cascade');;
            $table->timestamp('read_date');
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
        Schema::dropIfExists('reads');
    }
};
