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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('book_title');
            $table->string('book_year');
            $table->unsignedBigInteger('book_publisher');
            $table->foreign('book_publisher')->references('id')->on('publishers')->onDelete('cascade');;
            $table->unsignedBigInteger('book_author');
            $table->foreign('book_author')->references('id')->on('authors')->onDelete('cascade');;
            $table->string('book_file');
            $table->unsignedBigInteger('book_category');
            $table->foreign('book_category')->references('id')->on('categories')->onDelete('cascade');;
            $table->string('book_cover');
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
        Schema::dropIfExists('books');
    }
};
