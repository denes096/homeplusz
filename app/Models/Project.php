<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class Project extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'projects';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    function shortDesc($maxLength = 200) {
        if (mb_strlen($this->description, 'UTF-8') > $maxLength) {
            return mb_substr($this->description, 0, $maxLength, 'UTF-8') . '...';
        }
        return $this->description;
    }

    public function getImageUrls()
    {
        $r = [];
        foreach (json_decode($this->images) as $image) {
            $r[] = Storage::url($image);
        }

        return $r;
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
