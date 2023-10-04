<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerLeads extends Model
{

    protected $fillable = [
        'fname',
        'lname',
        'email',
        'phone',
        'message',
        'attachment',
        'job'
    ];
}
