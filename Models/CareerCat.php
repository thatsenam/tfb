<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerCat extends Model
{

    protected $fillable = [
        'label',
        'description',
        'created_by',
        'updated_by'
    ];

    public function posts()
    {
        return $this->hasMany('App\Models\Career', 'career_cat_id', 'id');
    }
}
