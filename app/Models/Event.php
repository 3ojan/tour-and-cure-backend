<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'clinic_id',
        'start_time',
        'end_time',
        'title',
        'location',
        'data'
    ];

    /**
     * auto-load relations
     */
    protected $with = ['employees'];


    /**
     * Define event-employee relation
     *
     * @return BelongsToMany
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_event', 'event_id', 'employee_id');
    }
}
