<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{


    protected $fillable = [
        'title',
        'meta_title',
        'date',
        'featured_image',
        'body',
        'published',
        'slug',
        'tagline',
        'facebook_message',
        'facebook_image',
        'facebook_video',
        'instagram_message',
        'instagram_image',
        'instagram_video',
        'hashtags',
        'banner_img',
        'meta_description',
        'meta_keywords',
        'structured_data',
        'created_by',
        'updated_by'
    ];

    protected $dates = ['date'];

    public function categories(){
        return $this->belongsToMany(BlogCat::class, 'blog_blog_cats', 'blog_id', 'blog_cat_id');
    }

    public function related(){
        return $this->belongsToMany(Blog::class, 'related_article', 'blog_id', 'related_id');
    }


    public function filePath($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/'.$tenant.'/images/blog/'. $this->$attribute;
    }

    public function revisions()
    {
        return $this->morphMany(Revision::class, 'revisionable')->latest('created_at');
    }

    public function friendly_url()
    {
        return $this->morphOne(FriendlyUrl::class, 'urlable');
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {

            $model->friendly_url()->delete();

            $base = 'upload/' . tenant('tnt_name') . '/images/blog/';

            $attributesToDelete = [
                'featured_image',
                'banner_img',
                'facebook_image',
                'instagram_image',
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
