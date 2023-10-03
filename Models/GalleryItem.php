<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{

    protected $fillable = [
        'gallery_id',
        'file',
        'label',
        'short_desc',
        'link',
        'display_order',
        'content',
        'button1_label',
        'button1_link',
        'button2_label',
        'button2_link',
        'created_by',
        'updated_by'
    ];



    public function filePath($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/'.$tenant.'/images/galleryItem/'. $this->$attribute;
    }



    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {

            $base = 'upload/' . tenant('tnt_name') . '/images/galleryItem/';

            $file = $base . $model->file;

            try {
                if (file_exists($file)) {
                    unlink($file);
                }
            } catch (\Exception $e) {}
        });

    }
}
