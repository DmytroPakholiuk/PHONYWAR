<?php

namespace App\Components;

use App\Models\RedisMessage;
use Illuminate\Support\Facades\Redis;

class RedisMessageFactory
{
    public function appendMessage(RedisMessage $message)
    {
        $savedMessage = Redis::get($message->receiver_number);
    }

    public function getMessages()
    {

    }


}
