<?php

namespace App\Models;

use App\Traits\Ownership;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TestimonialCat extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use Loggable;
    use Ownership;

    protected $fillable = [
        'label',
        'created_by',
        'updated_by'
    ];

    public function testimonials(){
        return $this->belongsToMany(Testimonial::class, 'tesimonial_tcats', 'tcat_id', 'testimonial_id ')->withTimestamps();
    }
}
