<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="EmployeeResource",
 *     title="Employee Resource",
 *     description="Represents an employee in the system.",
 *     @OA\Property(property="id", type="string", description="The unique identifier of the employee."),
 *     @OA\Property(property="name", type="string", description="The name of the employee."),
 *     @OA\Property(property="email", type="string", format="email", description="The email address of the employee."),
 *     @OA\Property(property="clinic", ref="#/components/schemas/ClinicResource", description="The clinic associated with the employee."),
 *     @OA\Property(property="role", type="string", description="The role of the employee."),
 *     @OA\Property(property="permissions", type="string", description="The permissions assigned to the employee."),
 *     @OA\Property(property="description", type="string", description="The description of the employee."),
 *     @OA\Property(property="phone", type="string", description="The phone number of the employee."),
 *     @OA\Property(property="type", type="string", description="The type of the employee."),
 *     @OA\Property(property="picture", type="string", description="The path to the picture of the employee."),
 * )
 */
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
