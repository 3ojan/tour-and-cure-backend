<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'user_roles';

    public $fillable = [
        'title',
        'name',
        'permissions',
    ];

    public $casts = [
        'permissions' => 'json'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public $option = [
        'name' => 'title'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role', 'name');
    }
}
