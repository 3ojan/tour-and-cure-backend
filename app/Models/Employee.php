<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Employee extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'description',
        'phone',
        'type'
    ];

    /**
     * auto-load relations
     */
    protected $with = ['user'];

    /**
     * Define employee->user relation
     *
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Define employee->media relation
     *
     * @return MorphOne
     */
    public function media()
    {
        return $this->morphOne(Media::class, 'model');
    }

    /**
     * Define employee-event relation
     *
     * @return BelongsToMany
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'employee_event', 'employee_id', 'event_id');
    }
}
