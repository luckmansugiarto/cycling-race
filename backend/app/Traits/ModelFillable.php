<?php
namespace App\Traits;

use App\Scopes\FlagAsDeletedScope;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Reusable code that can be use to grab all table columns.
 *
 * @since 1.0
 *
 * @version 1.0.0
 */
trait ModelFillable {

    public function getFillable()
    {
        return Schema::getColumnListing($this->getTable());
    }
}