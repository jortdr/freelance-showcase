<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\User;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index(User $friend)
    {
        return Inertia::render('Chat', [
            'friend' => $friend,
            'currentUser' => auth()->user(),
        ]);
    }

    public function messages(User $friend)
    {
        return ChatMessage::query()
            ->where(function ($query) use ($friend) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $friend->id);
            })
            ->orWhere(function ($query) use ($friend) {
                $query->where('sender_id', $friend->id)
                    ->where('receiver_id', auth()->id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('id', 'asc')
            ->get();
    }

    public function store(User $friend)
    {
        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $friend->id,
            'text' => request()->input('message')
        ]);

        broadcast(new MessageSent($message));

        return $message;
    }
}
