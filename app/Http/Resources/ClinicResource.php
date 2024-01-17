<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ClinicResource",
 *     title="Clinic Resource",
 *     description="Represents a clinic in the system.",
 *     @OA\Property(property="id", type="string", description="The unique identifier of the clinic."),
 *     @OA\Property(property="name", type="string", description="The name of the clinic."),
 *     @OA\Property(property="description", type="string", description="The description of the clinic."),
 *     @OA\Property(property="address", type="string", description="The address of the clinic."),
 *     @OA\Property(property="postcode", type="string", description="The postcode of the clinic."),
 *     @OA\Property(property="city", type="string", description="The city where the clinic is located."),
 *     @OA\Property(property="country_name", type="string", description="The name of the country where the clinic is located."),
 *     @OA\Property(property="phone", type="string", description="The phone number of the clinic."),
 *     @OA\Property(property="mobile", type="string", description="The mobile phone number of the clinic."),
 *     @OA\Property(property="email", type="string", format="email", description="The email address of the clinic."),
 *     @OA\Property(property="web", type="string", description="The website of the clinic."),
 *     @OA\Property(property="contact_person", type="string", description="The contact person of the clinic."),
 *     @OA\Property(property="contact_phone", type="string", description="The contact phone number of the clinic."),
 *     @OA\Property(property="contact_email", type="string", format="email", description="The contact email address of the clinic."),
 *     @OA\Property(property="latitude", type="string", description="The latitude of the clinic location."),
 *     @OA\Property(property="longitude", type="string", description="The longitude of the clinic location."),
 *     @OA\Property(property="created_by", ref="#/components/schemas/UserResource", description="The user who created the clinic."),
 *     @OA\Property(property="updated_by", ref="#/components/schemas/UserResource", description="The user who last updated the clinic."),
 *     @OA\Property(property="logo_image", type="string", description="The path to the logo image of the clinic."),
 *     @OA\Property(property="categories", type="array", @OA\Items(ref="#/components/schemas/CategoryResource"), description="The categories associated with the clinic."),
 * )
 */
class ClinicResource extends JsonResource
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
            'description' => $this->description,
            'address' => $this->address,
            'postcode' => $this->postcode,
            'city' => $this->city,
            'country_name' => optional($this->country)->name,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'web' => $this->web,

            'contact_person' => $this->contact_person,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,

            'latitude' => $this->latitude,
            'longitude' => $this->longitude,

            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
            'logo_image' => $this->logo_image,

            'categories' => CategoryResource::collection($this->whenLoaded('categories'))
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
