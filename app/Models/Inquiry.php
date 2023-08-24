<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'inquiries';

    protected $fillable = [
        'user_id',
        'service_type_id',
        'form_json'
    ];

    protected $casts = [
        'form_json' => 'array',
    ];
}
