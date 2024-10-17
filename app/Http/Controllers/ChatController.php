<?php

declare(strict_types=1);
// app/Http/Controllers/ChatController.php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\StoreMessageRequest;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class ChatController extends Controller
{
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
            ->get()->each(function ($message) {
                $message->text = Crypt::decryptString($message->text);
            });
    }

    public function store(StoreMessageRequest $request, User $friend)
    {
        $valid = $request->validated();
        $encrypted = Crypt::encryptString($valid['message']);
        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $friend->id,
            'text' => $encrypted,
        ]);

        $message->text = $valid['message'];

        broadcast(new MessageSent($message));

        return $message;
    }
}
