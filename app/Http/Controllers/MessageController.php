<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{
    use HttpResponses;


    /**
     * Display a listing of all contacts for specific user.
     */
    public function contacts()
    {
        $user_id = Auth::user()->id;

        $userIds = Message::where('sender_id', $user_id)
            ->orWhere('receiver_id', auth()->id())
            ->distinct()
            ->pluck('sender_id')
            ->merge(Message::where('receiver_id', $user_id)
                ->distinct()
                ->pluck('receiver_id'))
            ->unique();

        $contacts = User::whereIn('id', $userIds)->get();

        return $this->success(ContactResource::collection($contacts), 'All contacts fetched successfully!');
    }


    /**
     * Display all messages between user and contact.
     */
    public function chat(User $contact)
    {
        $user_id = Auth::user()->id;
        $contact_id = $contact->id;

        $messages = Message::where(function ($query) use ($user_id, $contact_id) {
            $query->where('sender_id', $user_id)->where('receiver_id', $contact_id)
                ->orWhere('sender_id', $contact_id)->where('receiver_id', $user_id);
        })->orderBy('created_at', 'asc')->get();

        return $this->success(MessageResource::collection($messages), 'Chat fetched successfully!');
    }
}
