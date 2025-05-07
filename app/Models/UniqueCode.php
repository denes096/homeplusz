<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniqueCode extends Model
{
    //
    protected $table = 'unique_code';

    public static function getNextCode()
    {
        return static::first()->code + 1;
    }
}
