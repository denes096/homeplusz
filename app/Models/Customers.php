<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customers extends Model
{
    use CrudTrait;
    use HasFactory;
    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'customers';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name_0', 'phone_0', 'email', 'status', 'ekod'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('customer')
            ->setDescriptionForEvent(function (string $eventName) {
                return match ($eventName) {
                    'created' => "Új vevő rögzítve: {$this->name_0}",
                    'updated' => "Vevő módosítva: {$this->name_0}",
                    'deleted' => "Vevő törölve: {$this->name_0}",
                    default => "Vevő esemény: {$this->name_0}",
                };
            });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function referens()
    {
        return $this->belongsTo(User::class, 'refId');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
