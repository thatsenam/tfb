<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{



    protected $guarded = [];


    /**
     * Get the template for this model.
     *
     * @return App\Models\Template
     */
    public function template()
    {
        return $this->belongsTo('App\Models\Template', 'template_id');
    }

    /**
     * Get the user for this model.
     *
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Get the pageRevision for this model.
     *
     * @return App\Models\PageRevision
     */
    public function pageRevision()
    {
        return $this->belongsTo('App\Models\PageRevision', 'page_revision_id');
    }

    public function custom_fields()
    {
        return $this->morphMany(CustomField::class, 'model')->where('is_published', 1);
    }

    public function revisions()
    {
        return $this->morphMany(Revision::class, 'revisionable')->latest('created_at');
    }

    public function publish()
    {
        $latestRevision = $this->revisions()->latest()->first();
        if ($latestRevision && $latestRevision->review_status === "approved") {
            $this->save();
        }
    }

    public function getCfAttribute()
    {
        $cf = CustomField::query()->where('model_type', Page::class)->where('model_id', $this->id)->pluck('value', 'key')->toJson();
        return json_decode($cf);
    }

    public function friendly_url()
    {
        return $this->morphOne(FriendlyUrl::class, 'urlable');
    }


    public function FilePath($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/' . $tenant . '/images/pages/' . $this->$attribute;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {

            $model->friendly_url()->delete();

            $base = 'upload/' . tenant('tnt_name') . '/images/pages/';

            $attributesToDelete = [
                'banner_image',
            ];

            foreach ($attributesToDelete as $attribute) {
                $file = $base . $model->{$attribute};

                try {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                } catch (\Exception $e) {
                }
            }
        });

    }


}
