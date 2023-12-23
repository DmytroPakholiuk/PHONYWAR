<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageStoreRequest;
use Illuminate\Http\Request;

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

        return $request->validated();
    }

    public function get(Request $request, $receiver_number)
    {

        return $receiver_number;
    }

}
