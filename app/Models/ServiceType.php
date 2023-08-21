<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceType extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'service_types';

    protected $fillable = [
        'code',
        'en',
        'hr',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class, 'clinic_service_type', 'service_type_id', 'clinic_id');
    }
}
