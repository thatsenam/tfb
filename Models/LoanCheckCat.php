<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanCheckCat extends Model
{


    protected $fillable = [
        'label',
        'display_order',
        'created_by',
        'updated_by'
    ];

    public function checks()
    {
        return $this->hasMany('App\Models\LoanCheck', 'loancheckcat_id', 'id');
    }

}
