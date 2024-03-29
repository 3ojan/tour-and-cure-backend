<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'clinic' => new ClinicResource($this->whenLoaded('clinic')),
            'role' => $this->user->role,
            'permissions' => $this->user->permissions,
            'description' => $this->description,
            'phone' => $this->phone,
            'type' => $this->type,
            'picture' => $this->media->path ?? null
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
