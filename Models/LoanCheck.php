<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanCheck extends Model
{



    protected $fillable = [
        'loancheckcat_id',
        'item',
        'sub_item',
        'image',
        'short_desciption',
        'display_order',
        'created_by',
        'updated_by'
    ];

    public function cat()
    {
        return $this->hasOne('App\Models\LoanCheckCat', 'id', 'loancheckcat_id');
    }

    public function filePath($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/'.$tenant.'/images/loanCheck/'. $this->$attribute;
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {

            $base = 'upload/' . tenant('tnt_name') . '/images/loanCheck/';

            $file = $base . $model->image;

            try {
                if (file_exists($file)) {
                    unlink($file);
                }
            } catch (\Exception $e) {}
        });

    }
}
