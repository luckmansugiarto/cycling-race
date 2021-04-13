<?php

namespace App\Models;

use App\Traits\ModelFillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

Abstract class BaseModel extends Model {
    use HasFactory, ModelFillable, SoftDeletes;

    public $timestamps = false;
    protected $hidden = ['deleted_at'];

    public function __construct($attributes = array())
    {
        $this->fillable($this->getFillable());        
        parent::__construct($attributes);
    }
}