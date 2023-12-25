<?php

namespace App\Components;

use App\Models\RedisMessage;
use Illuminate\Support\Facades\Redis;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

/**
 * This is a class for manipulation with RedisMessage classes because writing data manipulation
 * logic inside a model might not be very SingleResponsibility-ish
 */
class RedisMessageFactory
{
    /**
     * Appends message to Redis list while resetting the expiration for it
     *
     * @param RedisMessage $message
     * @return void
     * @throws \Nette\Utils\JsonException
     */
    public function appendMessage(RedisMessage $message): void
    {
        $json = Json::encode($message);
        Redis::lpush($message->receiver_number, $json);
        Redis::expire($message->receiver_number, 7200);
    }

    /**
     * gets a message array from that receiver number. Returns an empty array if nothing found
     *
     * @param $receiver_number
     * @return RedisMessage[]
     * @throws JsonException
     */
    public function getMessagesFor($receiver_number): array
    {
        $redisData = Redis::lrange($receiver_number, 0, -1);
        $returnCollection = [];
        foreach ($redisData as $datum){
            $returnCollection[] = $this->makeMessageFromJson($datum);
        }

        return $returnCollection;
    }

    /**
     * Makes a message from JSON-encoded string
     *
     * @param string $json
     * @return RedisMessage
     * @throws \Nette\Utils\JsonException
     */
    protected function makeMessageFromJson(string $json): RedisMessage
    {
        /**
         * @var \StdClass $decodedArray
         */
        $decodedArray = Json::decode($json);

        $redisMessage = new RedisMessage();
        $redisMessage->receiver_number = $decodedArray->receiver_number;
        $redisMessage->content = $decodedArray->content;
        $redisMessage->created_at = $decodedArray->created_at;

        return $redisMessage;
    }


}
