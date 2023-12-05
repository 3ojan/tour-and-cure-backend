<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
