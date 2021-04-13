<?php

namespace App\Models;

use App\Traits\ModelFillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory, ModelFillable;

    public $timestamps = false;
    protected $table = 'race';

    public function __construct($attributes = array())
    {
        $this->fillable($this->getFillable());
        parent::__construct($attributes);
    }

    public function club() {
        return $this->belongsTo(Club::class);
    }

    public function riders() {
        return $this->belongsToMany(Rider::class, 'race_result', 'race_id', 'rider_id');
    }
}
