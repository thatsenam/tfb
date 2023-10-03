<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanOfficers extends Model
{


    protected $fillable = [
        'user_id',
        'profile_image',
        'team_email',
        'title_id',
        'office_phone',
        'fax',
        'direct_phone',
        'cell_phone',
        'nmls',
        'apply_url',
        'vanity_domain',
        'testimonial',
        'facebook',
        'twitter',
        'google',
        'zillow',
        'yelp',
        'published',
        'about',
        'show_lo_page',
        'show_staff',
        'branch_manager',
        'long_bio',
        'linkedin',
        'leader',
        'leader_order',
        'leader_bio',
        'legacy_url',
        'video',
        'disclaimer',
        'fb_embed_or',
        'instagram',
        'spanish_apply',
        'banner',
        'display_order',
        'youtube',
        'facebook_pixel',
        'meta_keywords',
        'meta_description',
        'leader_img',
        'merit_item_1',
        'merit_item_2',
        'merit_item_3',
        'merit_item_4',
        'marketing_report_url',
        'external_blog',
        'external_event_calendar',
        'external_scheduler',
        'quick_start',
        'testimonial_embed',
        'testimonial_profile',
        'created_by',
        'updated_by',
        'primay_branch',
        'show_testimonial',
        'second_branch',
        'second_branch_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->orderBy('lname');
    }

    public function branches(){
        return $this->belongsToMany(Branches::class, 'banches_loanofficers', 'loanofficers_id', 'branches_id');
    }

    public function stlic()
    {
        return $this->belongsToMany(StateLic::class, 'los_stlic', 'loanofficers_id', 'statelic_id');
    }

    public function staff(){
        return $this->belongsToMany(StaffMembers::class, 'loanofficers_staffmembers', 'loanofficers_id', 'staffmembers_id');
    }

    public function title(){
        return $this->hasOne(Title::class, 'id', 'title_id');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'loanofficers_id', 'id');
    }

    public function licenses()
    {
        return $this->hasMany(Licenses::class, 'user_id', 'user_id');
    }

    public function getFilePathAttribute($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/'.$tenant.'/images/los/'.$this->$attribute;
    }


    public function friendly_url()
    {
        return $this->morphOne(FriendlyUrl::class, 'urlable')->withDefault([
            'friendlyUrl' => ""
        ]);
    }


    public function additionalFields()
    {
        return $this->morphMany(AdditionalField::class, 'fieldable');
    }


    protected static function boot()
    {

        parent::boot();

        static::deleting(function ($model) {
            $base = 'upload/' . tenant('tnt_name') . '/images/los/';

            $attributesToDelete = [
                'profile_image',
                'banner',
                'merit_item_1',
                'merit_item_2',
                'merit_item_3',
                'merit_item_4',
            ];

            foreach ($attributesToDelete as $attribute) {
                $file = $base . $model->{$attribute};

                try {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                } catch (\Exception $e) {}
            }
        });

    }

}
