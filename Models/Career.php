<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{


    protected $fillable = [
        'date_posted',
        'date_expires',
        'job_title',
        'career_cat_id',
        'description',
        'responder_email',
        'branches_id',
        'not_email_address',
        'not_sms_number',
        'auto_responder_id',
        'short_description',
        'created_by',
        'updated_by'
    ];

    protected $dates = ['date_posted', 'date_expires'];

    public function category(){
        return $this->hasOne('App\Models\CareerCat', 'id', 'career_cat_id');
    }

    public function branch(){
        return $this->belongsTo(Branches::class,'branches_id','id');
    }
    public function autoresponde(){
        return $this->belongsTo(AutoResponder::class,'auto_responder_id','id');
    }

}
