<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     title="User Resource",
 *     description="Represents a user.",
 *     @OA\Property(property="id", type="integer", description="The unique identifier of the user."),
 *     @OA\Property(property="name", type="string", description="The name of the user."),
 *     @OA\Property(property="email", type="string", format="email", description="The email address of the user."),
 *     @OA\Property(property="clinic", type="object", ref="#/components/schemas/ClinicResource", description="The clinic associated with the user."),
 *     @OA\Property(property="role", type="string", description="The role of the user."),
 *     @OA\Property(property="permissions", type="array", @OA\Items(type="string"), description="The permissions assigned to the user."),
 * )
 */
class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'clinic' => new ClinicResource($this->whenLoaded('clinic')),
            'role' => $this->role,
            'permissions' => $this->permissions
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
