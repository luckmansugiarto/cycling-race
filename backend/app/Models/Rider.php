<?php

namespace App\Models;

use App\Models\BaseModel;

class Rider extends BaseModel
{
    protected $table = 'rider';

    public function races() {
        return $this->belongsToMany(Race::class, 'race_result', 'rider_id', 'race_id')
            ->withPivot('finish_position', 'finish_time');
    }
}
