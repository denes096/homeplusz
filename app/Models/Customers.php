<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    protected $fillable = [
        'status',
        'refId',
        'kategoria',
        'name_0',
        'phone_0',
        'azonosito1_0',
        'azonosito2_0',
        'name_1',
        'phone_1',
        'name_2',
        'phone_2',
        'name_3',
        'phone_3',
        'name_4',
        'phone_4',
        'email',
        'address',
        'note',
    ];

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

    /**
     * Get the contacts for the customer
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(CustomerContact::class, 'customer_id');
    }

    /**
     * Get the documents for the customer
     */
    public function documents(): HasMany
    {
        return $this->hasMany(CustomerDocument::class, 'customer_id');
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
