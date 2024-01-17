<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CategoryResource",
 *     type="object",
 *     @OA\Property(property="id", type="string"),
 *     @OA\Property(property="code", type="string"),
 *     @OA\Property(property="en", type="string"),
 *     @OA\Property(property="hr", type="string"),
 *     @OA\Property(property="subcategories", type="array", @OA\Items(ref="#/components/schemas/CategoryResource")),
 * )
 */
class CategoryResource extends JsonResource
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
            'code' => $this->code,
            'en' => $this->en,
            'hr' => $this->hr,
            'subcategories' => CategoryResource::collection($this->descendants->toTree())
        ];
    }
}
