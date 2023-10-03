<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanTerm extends Model
{



    protected $fillable = [
        'term',
        'definition',
        'created_by',
        'updated_by'
    ];
}
