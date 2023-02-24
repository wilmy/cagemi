<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler;

class WebSocketController extends Controller
{
    public function handle(Request $request)
    {   
        return User::all();

        /*$ech = 'hola';
        $handler = new WebSocketHandler($ech);
        $handler->on('chat-message', function ($message) use ($handler) {
            error_log('Received message: ' . $message);
            $handler->broadcast()->emit('chat-message', $message);
        });
        $handler->handle();*/
    }
}
