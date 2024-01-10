<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
