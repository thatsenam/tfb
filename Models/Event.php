<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Event extends Model
{


    protected $dates = ['date'];

    protected $fillable = [
        'date',
        'title',
        'short_description',
        'long_description',
        'external_link',
        'published',
        'banner_img',
        'featured_img',
        'featured_vid',
        'created_by',
        'updated_by'
    ];


    public function filePath($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/'.$tenant.'/images/event/'. $this->$attribute;
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {

            $base = 'upload/' . tenant('tnt_name') . '/images/event/';

            $file = $base . $model->banner_img;
            $file2 = $base . $model->featured_img;

            try {
                if (file_exists($file)) {
                    unlink($file);
                }
            } catch (\Exception $e) {}

            try {
                if (file_exists($file2)) {
                    unlink($file2);
                }
            } catch (\Exception $e) {}


        });

    }
}
