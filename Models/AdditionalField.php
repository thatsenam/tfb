<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalField extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function fieldable()
    {
        return $this->morphTo();
    }
}
