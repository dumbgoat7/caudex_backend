<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_user',
        'review_book',
        'review_date',
        'review_rating',
        'review_comment',
    ];
}
