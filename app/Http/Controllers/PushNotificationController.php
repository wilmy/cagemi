<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ExponentPhpSDK\Expo;
use ExponentPhpSDK\Exceptions\ExpoException;

class PushNotificationController extends Controller
{
    public function index()
    {
        // Crea una instancia de Expo
        $expo = Expo::normalSetup();

        /*// Obtén el token de Expo push del usuario desde la base de datos
        $expoPushToken = "ExponentPushToken[Ju4iwvATwl70AMWqg8BSFq]";

        // Crea el mensaje que se enviará como notificación
        $message = [
            'to' => $expoPushToken,
            'sound' => 'default',
            'title' => 'Título de la notificación',
            'body' => 'Cuerpo de la notificación',
            'data' => ['someData' => 'goes here'],
        ];

        // Envía la notificación push
        try {
            $result = $expo->sendPushNotification($message);
        } catch (ExpoException $e) {
            // Maneja cualquier error de ExpoException
            // ...
            return $e;

        }*/

        $channelName = 'Jose';
        $recipient= 'ExponentPushToken[Ju4iwvATwl70AMWqg8BSFq]';
        //$recipient= 'ExponentPushToken[JqBpqzLiwNwM_v-541uMl9]';
        
        // You can quickly bootup an expo instance
        $expo = Expo::normalSetup();
        
        // Subscribe the recipient to the server
        $expo->subscribe($channelName, $recipient);
        
        // Build the notification data
        $notification = ['body' => 'Hello World!'];
        
        // Notify an interest with a notification
        $expo->notify([$channelName], $notification);

        return "enviado";
    }
}