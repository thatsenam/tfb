<?php

namespace App\Models;

use App\Traits\Ownership;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use Loggable;
    use Ownership;

    protected $guarded = [];
}
