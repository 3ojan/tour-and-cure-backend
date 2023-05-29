<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];

    // Define relationships or additional logic as needed
    /*
    public function services(): HasMany {
        return $this->hasMany(Services::class);
    }
    */
}
