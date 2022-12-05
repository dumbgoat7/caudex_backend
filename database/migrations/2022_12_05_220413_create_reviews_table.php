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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reviews_user');
            $table->foreign("reviews_user")->references("id")->on("users")->onDelete('cascade');;
            $table->unsignedBigInteger('reviews_book');
            $table->foreign("reviews_book")->references("id")->on("books")->onDelete('cascade');;
            $table->timestamp("reviews_date");
            $table->tinyInteger("reviews_rating");
            $table->string("reviews_comment");
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
        Schema::dropIfExists('reviews');
    }
};
