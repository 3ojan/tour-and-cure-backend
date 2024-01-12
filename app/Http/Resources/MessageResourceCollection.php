<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageResourceCollection extends ResourceCollection
{
    protected $contactId;
    protected $contactName;

    public function __construct($resource, $contactId, $contactName)
    {
        parent::__construct($resource);
        $this->contactId = $contactId;
        $this->contactName = $contactName;
    }

    public function toArray($request)
    {
        $authUserId = auth()->id();

        return [
            'contact_id' => $this->contactId,
            'conatact_name' => $this->contactName,
            'messages' => $this->collection->map(function ($message) use ($authUserId) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'timestamp' => $message->created_at,
                    'incoming' => $authUserId == $message->receiver_id,
                ];
            }),
        ];
    }
}
