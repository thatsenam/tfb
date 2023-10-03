<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leadership extends Model
{



    protected $table = 'leadership';


    protected $fillable = [
        'fname',
        'lname',
        'profile_image',
        'title',
        'bio',
        'phone',
        'email',
        'nmls',
        'published',
        'display_order',
        'created_by',
        'updated_by'
    ];


    public function getFilePathAttribute($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/'.$tenant.'/images/leadership/'. $this->$attribute;
    }




//    protected static function boot()
//    {
//        parent::boot();
//
//        static::retrieved(function ($leadership) {
//            $tenant = tenant('tnt_name');
//            $leadership->attributes['profile_image'] = url("/upload/{$tenant}/images/leadership/{$leadership->attributes['profile_image']}");
//        });
//    }


    protected static function boot()
    {

        parent::boot();

        static::deleting(function ($model) {
            $base = 'upload/' . tenant('tnt_name') . '/images/leadership/';

            $attributesToDelete = [
                'profile_image',
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
