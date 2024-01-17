<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="InquiryResource",
 *     title="Inquiry Resource",
 *     description="Represents an inquiry in the system.",
 *     @OA\Property(property="id", type="string", description="The unique identifier of the inquiry."),
 *     @OA\Property(property="user", ref="#/components/schemas/UserResource", description="The user associated with the inquiry."),
 *     @OA\Property(property="category", ref="#/components/schemas/CategoryResource", description="The category associated with the inquiry."),
 *     @OA\Property(property="form_json", type="string", description="JSON representation of the inquiry form."),
 * )
 */
class InquiryResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'form_json' => $this->form_json
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
