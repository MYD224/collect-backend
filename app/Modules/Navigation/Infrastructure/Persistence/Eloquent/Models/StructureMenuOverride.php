<?php

namespace App\Modules\Navigation\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StructureMenuOverride extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $guarded = [];
}
