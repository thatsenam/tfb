<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'lable',
        'page_id',
        'branch_id',
        'user_id',
        'created_by',
        'updated_by'
    ];

    public function items()
    {
        return $this->hasMany('App\Models\GalleryItem', 'gallery_id', 'id');
    }

    public function filePath($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/' . $tenant . '/images/galleryItem/' . $this->$attribute;
    }
}
