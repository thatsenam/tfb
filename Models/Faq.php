<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Faq extends Model
{

    protected $fillable = [
        'question',
        'faqcat_id',
        'answer',
        'learn_more_show',
        'learn_more_text',
        'learn_more_link',
        'display_order',
        'published',
        'created_by',
        'updated_by'
    ];

    public function category(){
        return $this->hasOne('App\Models\FaqCat', 'id', 'faqcat_id');
    }
}
