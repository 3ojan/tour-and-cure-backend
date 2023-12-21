<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'media_files';

    protected $fillable = [
        'mimetype',
        'name',
        'path',
        'ext',
        'size',
        'original_id',
        'width',
        'height',
        'model',
        'model_id',
        'attribute_name'
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
