<?php

namespace App\Models;

use App\Traits\ModelFillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    use HasFactory, ModelFillable;

    public $timestamps = false;
    protected $table = 'rider';

    public function __construct($attributes = array())
    {
        $this->fillable($this->getFillable());
        parent::__construct($attributes);
    }

    public function races() {
        return $this->belongsToMany(Race::class, 'race_result', 'rider_id', 'race_id')
            ->withPivot('finish_position', 'finish_time');
    }
}
