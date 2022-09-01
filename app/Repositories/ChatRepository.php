<?php

namespace App\Repositories;

use App\Models\ChatMessage;
use App\Models\ChatRoom;

class ChatRepository
{
    public function getRooms()
    {
        return ChatRoom::get();
    }
    public function getMessages($roomId)
    {
        return ChatMessage::where("chat_room_id", $roomId)
            ->with("user")
            ->orderBy("created_at", "DESC")->get();
    }
    public function newMesage($messageInput)
    {
        return ChatMessage::create($messageInput);
    }
}
