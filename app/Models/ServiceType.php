<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $table = 'service_types';

    protected $fillable = [
        'code',
        'en',
        'hr',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
