<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Alerts extends Model
{

    protected $fillable = [
        'title',
        'body',
        'global'
    ];
}
