<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="EventResource",
 *     title="Event Resource",
 *     description="Represents an event in the system.",
 *     @OA\Property(property="id", type="string", description="The unique identifier of the event."),
 *     @OA\Property(property="start_time", type="string", description="The start time of the event."),
 *     @OA\Property(property="end_time", type="string", description="The end time of the event."),
 *     @OA\Property(property="title", type="string", description="The title of the event."),
 *     @OA\Property(property="location", type="string", description="The location of the event."),
 *     @OA\Property(property="data", type="string", description="Additional data related to the event."),
 *     @OA\Property(property="employees", type="array", description="List of employees associated with the event.", @OA\Items(ref="#/components/schemas/EmployeeResource")),
 * )
 */
class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'title' => $this->title,
            'location' => $this->location,
            'data' => $this->data,
            'employees' => EmployeeResource::collection($this->employees),
        ];
    }

    public static function collection($data)
    {
        if (is_a($data, \Illuminate\Pagination\AbstractPaginator::class)) {
            $data->setCollection(
                $data->getCollection()->map(function ($listing) {
                    return new static($listing);
                })
            );

            return $data;
        }

        return parent::collection($data);
    }
}
