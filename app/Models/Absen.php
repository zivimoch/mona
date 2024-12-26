<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absen extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $table = 'absen';
    protected $guarded = [];
}
