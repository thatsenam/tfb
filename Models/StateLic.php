<?php

namespace App\Models;

use App\Traits\Ownership;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Image;

class StateLic extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use Loggable;
    use Ownership;

    protected $fillable = [
        'state',
        'license',
        'expiration',
        'banner',
        'disclaimer',
        'disclaimer_link',
        'created_by',
        'updated_by'
    ];


    public function filePath($attribute)
    {
        $tenant = tenant('tnt_name');
        return '/upload/'.$tenant.'/images/stateLic/'. $this->$attribute;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {

            $base = 'upload/' . tenant('tnt_name') . '/images/stateLic/';

            $file = $base . $model->banner;

            try {
                if (file_exists($file)) {
                    unlink($file);
                }
            } catch (\Exception $e) {}
        });

    }
}
