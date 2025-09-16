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
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Property extends Model
{
    use CrudTrait;
    use HasFactory;
    use LogsActivity;

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

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'price', 'rental_price', 'property_code', 'is_active', 'featured', 'ad_type'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('property')
            ->setDescriptionForEvent(function (string $eventName) {
                return match ($eventName) {
                    'created' => "Ingatlan hozzáadva: {$this->property_code}",
                    'updated' => "Ingatlan módosítva: {$this->property_code}",
                    'deleted' => "Ingatlan törölve: {$this->property_code}",
                    default => "Ingatlan esemény: {$this->property_code}",
                };
            });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (app()->runningInConsole()) {
                return;
            }

            $model->user_id = backpack_user()->id;

            $manager = new ImageManager(new Driver);
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

            $manager = new ImageManager(new Driver);
            $disk = 'public';
            $path = 'uploads';

            $finalPaths = [];

            $images = is_array($model->images) ? $model->images : json_decode($model->images, true);

            foreach ($images as $imgPath) {
                // Csak ha tényleges fájl elérési út (pl. uploads/kep.jpg)
                if (Storage::disk($disk)->exists($imgPath)) {
                    $fullPath = Storage::disk($disk)->path($imgPath);
                    $image = $manager->read($fullPath)
                        ->place(public_path('images/watermark.png'), 'center', 0, 0, 60);

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
        return '<a class="btn btn-sm btn-link" target="_blank" href="'.route('admin.property-image-downloader', ['unique_id' => $this->property_code]).'" data-toggle="tooltip" title="Képek letöltése"><i class="la la-download"></i> Képek letöltése</a>';
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
                return Storage::url("uploads/{$this->id}/".$image);
            }
        }

        // TODO no image defauklt
        return '/images/defaultProperty.png';
    }

    public function getFirstImageUrlAttribute()
    {
        $images = json_decode($this->images);

        if (! empty($images)) {
            return "uploads/{$this->id}/".$images[0];
        }

        return 'images/defaultProperty.png'; // alapértelmezett kép
    }

    public function getToggleActiveButton()
    {
        $isActive = $this->is_active;
        // A gomb szövege a következő műveletet mutatja, nem az aktuális állapotot
        $buttonClass = $isActive ? 'btn-warning' : 'btn-success';
        $buttonText = $isActive ? 'Deaktiválás' : 'Aktiválás';
        $icon = $isActive ? 'la-times' : 'la-check';

        return '<button class="btn btn-sm '.$buttonClass.' toggle-active-btn"
                data-property-id="'.$this->id.'"
                data-current-state="'.($isActive ? '1' : '0').'"
                data-toggle="tooltip"
                title="Kattints a váltáshoz">
                <i class="la '.$icon.'"></i> '.$buttonText.'
                </button>';
    }

    public function getMatchingSearchesButton()
    {
        $url = url("admin/property/{$this->id}/matching-searches");

        return '<a class="btn btn-sm btn-info" href="'.$url.'" title="Illeszkedő keresések megtekintése">
                <i class="la la-search"></i> Keresések
                </a>';
    }

    public function getImageUrls()
    {
        $r = [];
        foreach (json_decode($this->images) as $image) {
            $r[] = Storage::url("uploads/{$this->id}/".$image);
        }

        if (empty($r)) {
            $r[] = Storage::url('../images/defaultProperty.png');
        }

        return $r;
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function propertySubtype(): BelongsTo
    {
        return $this->belongsTo(PropertySubtype::class);
    }

    public function settlement(): BelongsTo
    {
        return $this->belongsTo(Settlement::class);
    }

    public function settlementPart(): BelongsTo
    {
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

    public function customerOffers()
    {
        return $this->belongsToMany(CustomerOffer::class, 'customer_offers', 'property_id', 'id')
            ->whereJsonContains('property_ids', $this->id);
    }

    public function isOfferedToCustomer($customerId, $searchId = null): bool
    {
        $query = CustomerOffer::where('customer_id', $customerId);

        if ($searchId) {
            $query->where('customer_search_id', $searchId);
        }

        // Check if this property ID is in the JSON array
        $offers = $query->get();

        foreach ($offers as $offer) {
            $propertyIds = is_string($offer->property_ids) ? json_decode($offer->property_ids, true) : $offer->property_ids;

            // Debug logging
            \Log::info('Checking offer', [
                'property_id' => $this->id,
                'offer_property_ids' => $propertyIds,
                'is_array' => is_array($propertyIds),
                'in_array' => is_array($propertyIds) ? in_array($this->id, $propertyIds) : false,
            ]);

            if (is_array($propertyIds) && in_array($this->id, $propertyIds)) {
                return true;
            }
        }

        return false;
    }

    public function getAdType(): string
    {
        return $this->ad_type == 'sell' ? 'eladó' : 'kiadó';
    }

    public function formatHUFMillions(int $decimals = 1, bool $withSuffix = true): string
    {
        $millions = $this->price / 1_000_000;
        $formatted = number_format($millions, $decimals, ',', ' ');

        return $withSuffix ? $formatted.' M Ft' : $formatted;
    }

    public function formatHUFThousands(int $decimals = 1, bool $withSuffix = true): string
    {
        $millions = $this->rental_price / 1_000;
        $formatted = number_format($millions, $decimals, ',', ' ');

        return $withSuffix ? $formatted.' E Ft' : $formatted;
    }

    public function getFullAddress(): string
    {
        if ($this->address) {
            return $this->address;
        }

        $parts = [];
        if ($this->settlement) {
            $parts[] = $this->settlement->name;
        }
        if ($this->settlementPart) {
            $parts[] = $this->settlementPart->name;
        }

        return implode(', ', $parts);
    }

    public function hasCoordinates(): bool
    {
        return ! is_null($this->latitude) && ! is_null($this->longitude);
    }

    public function toggleActive(): bool
    {
        $this->is_active = ! $this->is_active;
        $this->save();

        return $this->is_active;
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
