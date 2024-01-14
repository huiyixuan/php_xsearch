<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JogRecordModel extends Model
{


    use HasFactory;

    protected $table = 'jog_record';

    public $timestamps = false;
}
