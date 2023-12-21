<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Clinic extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'clinics';

    protected $fillable = [
        'name',
        'description',
        'address',
        'postcode',
        'city',
        'country_id',

        'latitude',
        'longitude',

        'web',
        'email',
        'mobile',
        'phone',

        'contact_person',
        'contact_email',
        'contact_phone',

        'created_by',
        'updated_by',

        'logo_image_id'
    ];

    // Automatically load necessary relations
    protected $with = ['logoImage', 'country', 'categories', 'createdBy', 'updatedBy'];

    /**
     * Define clinic-service_type relation
     *
     * @return BelongsToMany
     */
    public function serviceTypes(): BelongsToMany
    {
        return $this->belongsToMany(ServiceType::class, 'clinic_service_type', 'clinic_id', 'service_type_id');
    }

    /**
     * Define clinic-category relation
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_clinic', 'clinic_id', 'category_id');
    }

    /**
     * Define clinic-user relation
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Define clinic-logo relation
     *
     * @return BelongsTo
     */
    public function logoImage()
    {
        return $this->belongsTo('App\Models\LogoImage', 'logo_image_id')->withDefault();
    }

    /**
     * Define clinic-country relation
     *
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Define clinic-created_by relation
     *
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Define clinic-updated_by relation
     *
     * @return BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Define clinic-employees relation
     *
     * @return HasMany
     */
    public function employees() {
        return $this->hasMany(Employee::class);
    }

    /**
     * Define clinic->media relation
     *
     * @return MorphOne
     */
    public function media()
    {
        return $this->morphOne(Media::class, 'model');
    }
}
