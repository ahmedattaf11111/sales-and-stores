<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Http\Requests\MessageRequest;
use App\Repositories\ChatRepository;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChatController extends Controller
{
    private $chatRepository;
    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
        $this->middleware("auth");
    }

    public function getRooms()
    {
        return  $this->chatRepository->getRooms();
    }

    public function getMessages($roomId)
    {
        return $this->chatRepository->getMessages($roomId);
    }

    public function newMesage(MessageRequest $request)
    {
        $authUserId = JWTAuth::parseToken()->getPayload()->get("sub");
        $request->merge(["user_id" => $authUserId]);
        $message = $this->chatRepository->newMesage($request->input());
        broadcast(new NewChatMessage($message));
        return $message;
    }
}
