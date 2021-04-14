<?php

namespace App\Models;

use App\Models\BaseModel;

class Race extends BaseModel
{
    protected $table = 'race';

    public function club() {
        return $this->belongsTo(Club::class);
    }

    public function riders() {
        return $this->belongsToMany(Rider::class, 'race_result', 'race_id', 'rider_id')
            ->withPivot('finish_position', 'finish_time');
    }
}
