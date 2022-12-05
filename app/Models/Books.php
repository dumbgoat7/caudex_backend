<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_title',
        'book_year',
        'book_publisher',
        'book_author',
        'book_file',
        'book_category',
        'book_cover',
    ];
}
