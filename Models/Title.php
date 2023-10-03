<?php

namespace App\Models;

use App\Traits\Ownership;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Title extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;
    use Loggable;
    use Ownership;

    protected $table = "titles";

    protected $fillable = ['title', 'created_by', 'updated_by'];

    public function los()
    {
        return $this->hasMany(LoanOfficers::class, 'title_id', 'id');
    }

    public function staff()
    {
        return $this->hasMany(StaffMembers::class, 'title_id', 'id');
    }

}
