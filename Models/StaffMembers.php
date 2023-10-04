<?php

namespace App\Models;

use App\Traits\Ownership;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class StaffMembers extends Model
{


    protected $fillable = [
        'user_id',
        'phone',
        'nmls',
        'profile_image',
        'title_id',
        'display_order',
        'published',
        'asoc_type',
        'short_bio',
        'created_by',
        'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function los()
    {
        return $this->belongsToMany(LoanOfficers::class, 'loanofficers_staffmembers', 'staffmembers_id', 'loanofficers_id');
    }

    public function branches()
    {
        return $this->belongsToMany(Branches::class, 'branches_staffmember', 'staff_id', 'branch_id');
    }

    public function title()
    {
        return $this->hasOne(Title::class, 'id', 'title_id');
    }


    public function getFilePathAttribute($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/'.$tenant.'/images/staff/'. $this->$attribute;
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {

            $base = 'upload/' . tnt_name() . '/images/staff/';

            $file = $base . $model->profile_image;

            try {
                if (file_exists($file)) {
                    unlink($file);
                }
            } catch (\Exception $e) {}
        });

    }

}
