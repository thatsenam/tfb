<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendlyUrl extends Model
{
    use HasFactory;
    protected $fillable = ['friendlyUrl']; // Allow mass assignment of friendlyUrl field


    public function urlable()
    {
        return $this->morphTo();
    }
}
