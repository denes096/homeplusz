<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use CrudTrait;
    use HasFactory;
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['project_code', 'name', 'title', 'description', 'user_id', 'partner_id', 'storage_count', 'storage_type', 'is_required_storage'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('project')
            ->setDescriptionForEvent(function (string $eventName) {
                return match ($eventName) {
                    'created' => "Projekt létrehozva: {$this->project_code} - {$this->name}",
                    'updated' => "Projekt módosítva: {$this->project_code} - {$this->name}",
                    'deleted' => "Projekt törölve: {$this->project_code} - {$this->name}",
                    default => "Projekt esemény: {$this->project_code} - {$this->name}",
                };
            });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            // Automatically set the user_id if not provided
            if (empty($model->user_id)) {
                $model->user_id = backpack_user()->id ?? null;
            }

            $manager = new ImageManager(new Driver);
            $disk = 'public';
            $path = 'uploads';

            $finalPaths = [];

            $images = is_array($model->images) ? $model->images : (is_string($model->images) ? json_decode($model->images, true) : []);

            foreach ($images as $imgPath) {
                // Eltávolítjuk az "uploads/" prefixet a helyes elérési út érdekében
                $cleanImgPath = str_replace('uploads/', '', $imgPath);

                // Csak ha tényleges fájl elérési út (pl. kep.jpg)
                if (Storage::disk($disk)->exists($cleanImgPath)) {
                    $fullPath = Storage::disk($disk)->path($cleanImgPath);
                    $image = $manager->read($fullPath)
                        ->place(public_path('images/watermark.png'), 'center');

                    // Felülírja a meglévő fájlt a helyes elérési úttal
                    Storage::disk($disk)->put($cleanImgPath, (string) $image->encode());

                    $finalPaths[] = $cleanImgPath;
                } else {
                    // Ha valamiért nem létező, csak hozzáadjuk
                    $finalPaths[] = $cleanImgPath;
                }
            }

            // JSON-be visszarakjuk
            $model->images = json_encode($finalPaths);
        });

        static::updating(function ($model) {
            $manager = new ImageManager(new Driver);
            $disk = 'public';
            $path = 'uploads';

            $finalPaths = [];

            $images = is_array($model->images) ? $model->images : (is_string($model->images) ? json_decode($model->images, true) : []);

            foreach ($images as $imgPath) {
                // Eltávolítjuk az "uploads/" prefixet a helyes elérési út érdekében
                $cleanImgPath = str_replace('uploads/', '', $imgPath);

                // Csak ha tényleges fájl elérési út (pl. kep.jpg)
                if (Storage::disk($disk)->exists($cleanImgPath)) {
                    $fullPath = Storage::disk($disk)->path($cleanImgPath);
                    $image = $manager->read($fullPath)
                        ->place(public_path('images/watermark.png'), 'center', 0, 0, 60);

                    // Felülírja a meglévő fájlt a helyes elérési úttal
                    Storage::disk($disk)->put($cleanImgPath, (string) $image->encode());

                    $finalPaths[] = $cleanImgPath;
                } else {
                    // Ha valamiért nem létező, csak hozzáadjuk
                    $finalPaths[] = $cleanImgPath;
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

    public function partner()
    {
        return $this->belongsTo(Partners::class, 'partner_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shortDesc($maxLength = 200)
    {
        if (mb_strlen($this->description, 'UTF-8') > $maxLength) {
            return mb_substr($this->description, 0, $maxLength, 'UTF-8').'...';
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
