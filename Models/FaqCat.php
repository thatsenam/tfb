<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqCat extends Model
{


    protected $fillable = [
        'label',
        'display_order',
        'created_by',
        'updated_by'
    ];

    public function faqs()
    {
        return $this->hasMany('App\Models\Faq', 'faqcat_id', 'id');
    }
}
