<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
    protected $guarded = [];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (app()->runningInConsole()) {
                return;
            }

            $model->user_id = backpack_user()->id;

            $manager = new ImageManager(new Driver());
            $disk = 'public';
            $path = 'uploads';

            $finalPaths = [];

            $images = is_array($model->images) ? $model->images : json_decode($model->images, true);

            foreach ($images as $imgPath) {
                // Csak ha tényleges fájl elérési út (pl. uploads/kep.jpg)
                if (Storage::disk($disk)->exists($imgPath)) {
                    $fullPath = Storage::disk($disk)->path($imgPath);
                    $image = $manager->read($fullPath)
                        ->place(public_path('images/watermark.png'), 'center');

                    // Felülírja a meglévő fájlt
                    Storage::disk($disk)->put($imgPath, (string) $image->encode());

                    $finalPaths[] = $imgPath;
                } else {
                    // Ha valamiért nem létező, csak hozzáadjuk
                    $finalPaths[] = $imgPath;
                }
            }

            // JSON-be visszarakjuk
            $model->images = json_encode($finalPaths);
        });

        static::updating(function ($model) {
            if (app()->runningInConsole()) {
                return;
            }

            $manager = new ImageManager(new Driver());
            $disk = 'public';
            $path = 'uploads';

            $finalPaths = [];

            $images = is_array($model->images) ? $model->images : json_decode($model->images, true);

            foreach ($images as $imgPath) {
                // Csak ha tényleges fájl elérési út (pl. uploads/kep.jpg)
                if (Storage::disk($disk)->exists($imgPath)) {
                    $fullPath = Storage::disk($disk)->path($imgPath);
                    $image = $manager->read($fullPath)
                        ->place(public_path('images/watermark.png'), 'center', 0 , 0, 60);

                    // Felülírja a meglévő fájlt
                    Storage::disk($disk)->put($imgPath, (string) $image->encode());

                    $finalPaths[] = $imgPath;
                } else {
                    // Ha valamiért nem létező, csak hozzáadjuk
                    $finalPaths[] = $imgPath;
                }
            }

            // JSON-be visszarakjuk
            $model->images = json_encode($finalPaths);
        });
    }

    public function openGoogle($crud = false)
    {
        return '<a class="btn btn-sm btn-link" target="_blank" href="' . route('admin.property-image-downloader', ['unique_id' => $this->property_code]) . '" data-toggle="tooltip" title="Képek letöltése"><i class="la la-download"></i> Képek letöltése</a>';
    }

    public function getFormattedPrice()
    {
        if (floor($this->price) == $this->price) {
            // egész szám, nincs tizedes rész
            return number_format($this->price, 0, ',', ' ');
        } else {
            // van tizedes rész, 2 tizedes jegy
            return number_format($this->price, 2, ',', ' ');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function getMainImageUrl()
    {

        $images = json_decode($this->images);

        if ($images != null) {
            foreach ($images as $image) {
                return Storage::url("uploads/{$this->id}/" . $image);
            }
        }
        //TODO no image defauklt
        return '/images/defaultProperty.png';
    }

    public function getFirstImageUrlAttribute()
    {
        $images = json_decode($this->images);

        if (!empty($images)) {
            return "uploads/{$this->id}/" . $images[0];
        }
        return 'images/defaultProperty.png'; // alapértelmezett kép
    }

    public function getCustomActionButton()
    {
        $url = url("admin/property/{$this->id}/custom-action");

        return '<a class="btn btn-sm btn-success" href="'.$url.'">Aktiválás/Deaktiválás</a>';
    }

    public function getImageUrls()
    {
        $r = [];
        foreach (json_decode($this->images) as $image) {
            $r[] = Storage::url("uploads/{$this->id}/" . $image);
        }

        if (empty($r)) {
            $r[] = Storage::url('../images/defaultProperty.png');
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

    public function getAdType(): string
    {
        return $this->ad_type == 'sell' ? 'eladó' : 'kiadó';
    }

    function formatHUFMillions( int $decimals = 1, bool $withSuffix = true): string
    {
        $millions = $this->price / 1_000_000;
        $formatted = number_format($millions, $decimals, ',', ' ');
        return $withSuffix ? $formatted . ' M Ft' : $formatted;
    }

    function formatHUFThousands( int $decimals = 1, bool $withSuffix = true): string
    {
        $millions = $this->price / 1_000;
        $formatted = number_format($millions, $decimals, ',', ' ');
        return $withSuffix ? $formatted . ' M Ft' : $formatted;
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
