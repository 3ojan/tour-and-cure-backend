<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ContactResource",
 *     title="Contact Resource",
 *     description="Represents a contact in the system.",
 *     @OA\Property(property="id", type="string", description="The unique identifier of the contact."),
 *     @OA\Property(property="name", type="string", description="The name of the contact."),
 * )
 */
class ContactResource extends JsonResource
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
            'name' => $this->name
        ];
    }
}
