<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStructure extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'structure_id',
        'created_by_id',
        'last_updated_by_id'
    ];

    public $incrementing = false;
}
