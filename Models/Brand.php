<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{

    protected $guarded = [];


    public function filePath($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/'.$tenant.'/images/brand/'. $this->$attribute;
    }
}
