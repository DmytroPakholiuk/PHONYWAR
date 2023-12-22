<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageStoreRequest;
use Illuminate\Http\Request;

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
