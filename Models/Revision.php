<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function revisionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approve()
    {
        $this->review_status = "approved";
        $this->reviewed_by = auth()->id();

        $this->save();
    }

    public function decline()
    {
        $this->review_status = "declined";
        $this->reviewed_by = auth()->id();
        $this->save();
    }
}
