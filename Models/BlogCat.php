<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCat extends Model
{

    protected $fillable = [
        'label',
        'created_by',
        'updated_by'
    ];

    public function blogs()
    {
        return $this->belongsToMany('App\Models\Blog', 'blog_blog_cats', 'blog_cat_id', 'blog_id');
    }

}
