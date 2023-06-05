<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
    //
    public function test()
    {
        $user = User::find(1);
        $message = Message::create([
            'user_id' => $user->id,
            'message' => 'Some textual message'
        ]);
        broadcast(new MessageSent($user, $message))->toOthers();
    }
}
