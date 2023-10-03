<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CustomTemplate extends Model
{

    use HasUuids;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'custom_templates';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'name',
                  'content',
                  'style',
                  'thumbnail',
                  'is_active'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];




    public function tenants()
    {
        return $this->belongsToMany(Tenant::class);
    }
}
