<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AutoResponder extends Model
{
    protected $fillable = [
        'purpose',
        'resp_recipient',
        'from_email',
        'subject',
        'body',
        'status',
        'notes',
        'created_by',
        'updated_by'
    ];
}
