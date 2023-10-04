<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class DoDontCat extends Model
{


    protected $fillable = [
        'label',
        'created_by',
        'updated_by'
    ];

    public function dodonts(){
        return $this->belongsToMany(Dodont::class, 'dodont_ddcat', 'dodontcat_id', 'dodonts_id')->withTimestamps();
    }


}
