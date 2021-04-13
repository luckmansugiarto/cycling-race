<?php

namespace App\Models;

use App\Models\BaseModel;

class Club extends BaseModel
{
    protected $table = 'club';   

    public function races() {
        return $this->hasMany(Race::class);
    }
}
