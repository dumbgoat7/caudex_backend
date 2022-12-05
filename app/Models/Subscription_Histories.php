<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription_Histories extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription',
        'subscription_start',
        'subscription_expired',
    ];
}
