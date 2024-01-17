<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="MessageResourceCollection",
 *     title="Message Resource Collection",
 *     description="Represents a collection of messages for a contact.",
 *     @OA\Property(property="contact_id", type="integer", description="The unique identifier of the contact."),
 *     @OA\Property(property="contact_name", type="string", description="The name of the contact."),
 *     @OA\Property(property="messages", type="array", @OA\Items(
 *         @OA\Property(property="id", type="integer", description="The unique identifier of the message."),
 *         @OA\Property(property="message", type="string", description="The content of the message."),
 *         @OA\Property(property="timestamp", type="string", format="date-time", description="The timestamp when the message was created."),
 *         @OA\Property(property="incoming", type="boolean", description="Indicates whether the message is incoming or outgoing."),
 *     )),
 * )
 */
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
