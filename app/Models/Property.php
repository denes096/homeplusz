<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'properties';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            $property->user_id = backpack_user()->id;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function getMainImageUrl()
    {
        return Storage::url(json_decode($this->images)[0]);
    }

    public function getImageUrls()
    {
        $r = [];
        foreach (json_decode($this->images) as $image) {
            $r[] = Storage::url($image);
        }

        return $r;
    }

    public function propertyType(): BelongsTo{
        return $this->belongsTo(PropertyType::class);
    }
    public function propertySubtype(): BelongsTo{
        return $this->belongsTo(PropertySubtype::class);
    }

    public function settlement(): BelongsTo{
        return $this->belongsTo(Settlement::class);
    }

    public function settlementPart(): BelongsTo{
        return $this->belongsTo(SettlementPart::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(PropertyAttribute::class)->withPivot('value');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
