<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'sender_name' => $this->sender->name,
            'receiver_id' => $this->receiver_id,
            'receiver_name' => $this->receiver->name,
            'message' => $this->message,
            'timestamp' => $this->created_at
        ];
    }
}
