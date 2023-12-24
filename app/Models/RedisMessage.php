<?php

namespace App\Models;

use Illuminate\Contracts\Support\Arrayable;

/**
 * This model represents a message that we would store in Redis
 */
class RedisMessage implements Arrayable
{
    public string $receiver_number;
    public string $content;
    public string $created_at;

//    public function setContent($content): void
//    {
//        $this->content = $content;
//    }
//    public function setId($id): void
//    {
//        $this->id = $id;
//    }
//    public function set
    public function toArray()
    {
        return [
            "content" => $this->content,
            "receiver_number" => $this->receiver_number,
            "created_at" => $this->created_at
        ];
    }
}
