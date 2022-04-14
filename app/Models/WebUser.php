<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class WebUser extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'subscribed_websites', 'email', 'seen_posts'
    ];
}
