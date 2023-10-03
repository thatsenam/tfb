<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{

    protected $fillable = [
        'branchName',
        'logo',
        'address1',
        'address2',
        'city',
        'state',
        'zip',
        'phone',
        'fax',
        'nmls',
        'webAddress',
        'applyUrl',
        'testimonial',
        'published',
        'region_id',
        'content',
        'physical_branch',
        'banner_img',
        'branch_email',
        'url_forward',
        'toll_free',
        'dba',
        'child_branch',
        'parent_branch_id',
        'display_map',
        'display_branch',
        'legacy_url',
        'video',
        'shared_area_header',
        'shared_area_body',
        'shared_area_tab',
        'disclaimer',
        'fb_embed_or',
        'facebook',
        'twitter',
        'google',
        'zillow',
        'yelp',
        'linkedin',
        'instagram',
        'youtube',
        'facebook_pixel',
        'body_headline',
        'body_button_link',
        'body_button_label',
        'meta_keywords',
        'meta_description',
        'show_lo_apply',
        'ad1_image',
        'ad2_image',
        'marketing_report_url',
        'testimonial_embed',
        'testimonial_profile',
        'created_by',
        'updated_by',
        'meta_title',
        'address_lat',
        'address_lng',
    ];

    public function los()
    {
        return $this->belongsToMany(LoanOfficers::class, 'banches_loanofficers', 'branches_id', 'loanofficers_id');
    }

    public function staff()
    {
        return $this->belongsToMany(StaffMembers::class, 'branches_staffmember', 'branch_id', 'staff_id');
    }

    public function careers()
    {
        return $this->hasMany(Career::class, 'branches_id', 'id');
    }


    public function getBannerImgPathAttribute()
    {
        $tenant = tenant('tnt_name');
        return '/upload/' . $tenant . '/images/branch/' . $this->banner_img;
    }

    public function getLogoPathAttribute()
    {
        $tenant = tenant('tnt_name');
        return '/upload/' . $tenant . '/images/branch/' . $this->logo;
    }

    public function getAd1ImagePathAttribute()
    {
        $tenant = tenant('tnt_name');
        return '/upload/' . $tenant . '/images/branch/' . $this->ad1_image;
    }

    public function getAd2ImagePathAttribute()
    {
        $tenant = tenant('tnt_name');
        return '/upload/' . $tenant . '/images/branch/' . $this->ad2_image;
    }

    public function getFilePathAttribute($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/' . $tenant . '/images/branch/' . $this->$attribute;
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
            $base = 'upload/' . tenant('tnt_name') . '/images/branch/';

            $attributesToDelete = [
                'logo',
                'banner_img',
                'ad1_image',
                'ad2_image',
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
