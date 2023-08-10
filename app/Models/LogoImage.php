<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogoImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'path',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function clinics()
    {
        return $this->hasMany('App\Models\Clinic', 'logo_image_id');
    }
}
