<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanOption extends Model
{


    protected $fillable = [
        'label',
        'short_description',
        'long_description',
        'content_p',
        'display_order',
        'published',
        'banner',
        'cta_img',
        'title',
        'meta_keywords',
        'meta_description',
        'show_testimonials',
        'created_by',
        'updated_by',
        'template_id'
    ];


    public function custom_fields()
    {
        return $this->morphMany(CustomField::class, 'model');
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function getCfAttribute()
    {
        $cf = CustomField::query()->where('model_type', LoanOption::class)->where('model_id', $this->id)->pluck('value', 'key')->toJson();
        return json_decode($cf);
    }

    public function friendly_url()
    {
        return $this->morphOne(FriendlyUrl::class, 'urlable');
    }

    public function filePath($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/' . $tenant . '/images/loanOption/' . $this->$attribute;
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
