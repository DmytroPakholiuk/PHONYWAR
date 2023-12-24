<?php

namespace App\Http\Controllers;

use App\Components\RedisMessageFactory;
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
 *                          example=09655555555,
 *                          description="A 10-digit phone number. No spaces or hyphens."
 *                      ),
 *                      @OA\Property(
 *                          property="content",
 *                          type="string",
 *                          example="Lorem Ipsum",
 *                          description="Text content for the message."
 *                      ),
 *                  ),
 *             }
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message", type="string", example="Message sent",
 *             ),
 *             @OA\Property(
 *                 property="status", type="integer", example=200
 *             ),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Content",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message", type="string", example="The content field is required. (and 1 more error)"
 *             ),
 *             @OA\Property(
 *                 property="errors", type="object",
 *                 @OA\Property(
 *                     property="receiver_number", type="array", @OA\Items(
 *                         type="string",
 *                         example="The receiver number field format is invalid."
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="content", type="array", @OA\Items(
 *                         type="string",
 *                         example="The content field is required."
 *                     )
 *                 )
 *             )
 *         )
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
 *         @OA\Examples(example="int", value="0000000000", summary="An int value."),
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message", type="string", example="The messages for number 0000000000:"
 *             ),
 *             @OA\Property(
 *                 property="status", type="integer", example=200
 *             ),
 *             @OA\Property(
 *                 property="data", type="array", @OA\Items(
 *                     @OA\Property(property="receiver_number", type="integer", example=0000000000),
 *                     @OA\Property(property="content", type="string", example="Lorem Ipsum"),
 *                     @OA\Property(property="created_at", type="string", example="Sun Dec 24 2023 19:29:55 GMT+0000"),
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Not found",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message", type="string", example="Not found. The data you are seeking either does not exist or is expired."
 *             ),
 *             @OA\Property(
 *                 property="status", type="integer", example=404
 *             )
 *         )
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

        $factory = new RedisMessageFactory();
        $factory->appendMessage($message);

        return [
            "message" => "Message sent",
            "status" => 200
        ];
    }

    public function get(Request $request, $receiver_number)
    {
        $factory = new RedisMessageFactory();

        $data = $factory->getMessagesFor($receiver_number);
        if (empty($data)){
            throw new NotFoundHttpException();
        }

        return [
            "message" => "The messages for number $receiver_number:",
            "data" => $data,
            "status" => 200
        ];
    }

}
