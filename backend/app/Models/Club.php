<?php

namespace App\Models;

use App\Traits\ModelFillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory, ModelFillable;

    public $timestamps = false;
    protected $table = 'club';

    public function __construct($attributes = array())
    {
        $this->fillable($this->getFillable());
        parent::__construct($attributes);
    }

    public function races() {
        return $this->hasMany(Race::class);
    }
}
