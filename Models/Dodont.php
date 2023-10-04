<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dodont extends Model
{




    protected $fillable = [
        'type',
        'item',
        'display_order',
        'created_by',
        'updated_by'
    ];

    public function ddcat(){
        return $this->belongsToMany(DoDontCat::class, 'dodont_ddcat', 'dodonts_id', 'dodontcat_id')->withTimestamps();
    }
}
