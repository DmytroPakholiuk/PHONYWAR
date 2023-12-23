<?php

namespace App\Components;

use App\Models\RedisMessage;
use Illuminate\Support\Facades\Redis;
use Nette\Utils\Json;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function getMessagesFor($receiver_number)
    {
        $redisData = Redis::lrange($receiver_number, 0, -1);
        if (empty($redisData)){
            throw new NotFoundHttpException();
        }

        $returnCollection = [];
        foreach ($redisData as $datum){
            $returnCollection[] = $this->makeMessageFromJson($datum);
        }

        return $returnCollection;
    }

    protected function makeMessageFromJson(string $json)
    {
        /**
         * @var \StdClass $decodedArray
         */
        return Json::decode($json);
        $decodedArray = Json::decode($json);
        var_dump($decodedArray);die();
    }


}
