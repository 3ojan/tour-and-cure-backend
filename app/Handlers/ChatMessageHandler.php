<?php

namespace App\Handlers;

use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;
use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler;
use Ratchet\ConnectionInterface;

class ChatMessageHandler implements WebSocketHandler
{
    protected $channelManager;

    public function __construct(ChannelManager $channelManager)
    {
        $this->channelManager = $channelManager;
    }

    public function onOpen(ConnectionInterface $connection)
    {
        // Handle the WebSocket connection open event
    }

    public function onMessage(ConnectionInterface $connection, $message)
    {
        $payload = json_decode($message, true);

        if ($payload['event'] === 'chat-message') {
            // Handle the chat-message event logic
            $chatMessage = $payload['data']['message'];
            
            // Broadcast the message to all connected clients
            $this->channelManager->broadcastToAll([
                'event' => 'chat-message',
                'data' => [
                  'message' => $chatMessage
                ]
            ]);
        }
    }

    public function onClose(ConnectionInterface $connection)
    {
        // Handle the WebSocket connection close event
    }

    public function onError(ConnectionInterface $connection, \Exception $exception)
    {
        // Handle WebSocket error event
    }
}
