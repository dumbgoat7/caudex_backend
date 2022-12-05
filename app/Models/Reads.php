<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reads extends Model
{
    use HasFactory;

    protected $fillable = [
        'read_user',
        'read_book',
        'read_date',
    ];
}
