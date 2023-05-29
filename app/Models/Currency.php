<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'iso',
        'num',
        'decimal_place',
        'name_en',
        'name_hr',
        'used_by',
    ];

    public $searchable = [
        'iso',
        'num',
        'name_en',
        'name_hr',
        'used_by',
    ];
}
