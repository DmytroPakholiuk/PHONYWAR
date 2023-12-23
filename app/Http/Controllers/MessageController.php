<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageStoreRequest;
use App\Models\RedisMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @OA\Post(
 *     path="/api/messages/send",
 *     summary="Send a message to the phone number",
 *     tags={"Messages"},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             allOf={
 *                  @OA\Schema(
 *                      @OA\Property(
 *                          property="receiver_number",
 *                          type="integer",
 *                          example=0965555555
 *                      ),
 *                      @OA\Property(
 *                          property="content",
 *                          type="string",
 *                          example="Lorem Ipsum"
 *                      ),
 *                  ),
 *             }
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *     ),
 * ),
 *
 * ///////////////////////////////////////////////////////////////////////////////////////
 *
 * @OA\Get(
 *     path="/api/messages/{receiver_number}",
 *
 *     tags={"Messages"},
 *
 *     @OA\Parameter(
 *         description="Phone number of that receiver",
 *         in="path",
 *         name="receiver_number",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         @OA\Examples(example="int", value="0965555555", summary="An int value."),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *     ),
 * )
 */
class MessageController extends Controller
{

    public function store(MessageStoreRequest $request)
    {
        $validated = $request->validated();

        $message = new RedisMessage();

        $message->content = $validated["content"];
        $message->receiver_number = $validated["receiver_number"];
        $message->created_at = Carbon::now()->toString();

        return $message;
    }

    public function get(Request $request, $receiver_number)
    {
        $data = Redis::get($receiver_number);

        if ($data === null){
            throw new NotFoundHttpException();
        }

        return $data;
    }

}
