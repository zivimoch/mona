<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShiftRules extends Model
{
    /** @use HasFactory<\Database\Factories\ShiftRulesFactory> */
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $table = 'shift_rules';
    protected $guarded = [];
}
