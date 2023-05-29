<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'iso',
        'alpha2',
        'alpha3',
        'code',
        'iso3166_2',
        'tld',
        'region',
        'sub_region',
        'intermediate_region',
        'region_code',
        'sub_region_code',
        'intermediate_region_code'
    ];

    public $searchable = [
        'name',
        'iso',
        'alpha2',
        'alpha3',
        'code',
        'iso3166_2',
        'tld',
        'region',
        'sub_region',
        'intermediate_region',
        'region_code',
        'sub_region_code',
        'intermediate_region_code'
    ];

    public $rules = [
        'name' => 'required',
        'iso' => 'required',
    ];
}
