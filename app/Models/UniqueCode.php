<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniqueCode extends Model
{
    //
    protected $table = 'unique_code';

    public $timestamps = false;

    protected $primaryKey = 'code'; // ez a meglévő egyetlen oszlop

    public $incrementing = false;   // nem automatikusan növekvő

    protected $keyType = 'int';     // ha integer típusú

    protected $guarded = [];

    public static function getNextCode()
    {
        return static::getCurrentCode() + 1;
    }

    public static function getCurrentCode()
    {
        return static::first()?->code ?? 0;
    }

    public static function updateCode(int $code)
    {
        $record = static::first(); // vagy where(...) ha több van

        if (! $record) {
            $record = new static;
            $record->code = $code;
            $record->save();
        } else {
            $record->code = $code;
            $record->save();
        }
    }
}
