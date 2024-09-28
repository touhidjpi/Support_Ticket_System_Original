<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Eticket extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'userID',
        'replyID',
        'subject',
        'descriptions',
        'token_type',
    ];
}
