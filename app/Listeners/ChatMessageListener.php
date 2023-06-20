<?php

namespace App\Listeners;

use App\Events\ChatMessageEvent;

class ChatMessageListener
{
    public function handle(ChatMessageEvent $event)
    {
        // Access the event data
        $message = $event->message;
        // Perform your logic, such as saving the message to the database, broadcasting to other clients, etc.

        // Example: Broadcasting the message to the chat-channel
        event(new ChatMessageEvent($message));
    }
}
