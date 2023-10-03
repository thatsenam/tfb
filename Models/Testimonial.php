<?php

namespace App\Models;

use App\Traits\Ownership;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Testimonial extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;
    use Loggable;
    use Ownership;

    protected $fillable = [
        'label',
        'date',
        'body',
        'footer',
        'branches_id',
        'loanofficers_id',
        'video',
        'published',
        'created_by',
        'updated_by'
    ];

    protected $dates = ['date'];

    public function branch(){
        return $this->hasOne('App\Models\Branches', 'id', 'branches_id');
    }

    public function lo(){
        return $this->hasOne('App\Models\LoanOfficers', 'id', 'loanofficers_id');
    }

    public function cats(){
        return $this->belongsToMany(TestimonialCat::class, 'tesimonial_tcats', 'testimonial_id', 'tcat_id')->withTimestamps();
    }

}
