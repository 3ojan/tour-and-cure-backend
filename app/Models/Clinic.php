<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clinic extends Model
{
    use HasFactory;

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

        'logo_image_id',
    ];

      // Automatically load the logoImage relationship when fetching Clinic data
    protected $with = ['logoImage'];

    // Define relationships or additional logic as needed
    public function serviceTypes(): BelongsToMany
    {
        return $this->belongsToMany(ServiceType::class, 'clinic_service_type', 'clinic_id', 'service_type_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    public function logoImage()
    {
        return $this->belongsTo('App\Models\LogoImage', 'logo_image_id')->withDefault();
    }
}
