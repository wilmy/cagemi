<?php
 
namespace App\Http\Controllers\Notifications;
  
use ExponentPhpSDK\Expo;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

//use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

class NewNotifications extends Controller
{
    public function index()
    {
        $dataUser = DB::table('users')->find(7); 
        $nombre_usu = $dataUser->name;

        $list_token = array(); 
        $dataUserTokens = DB::table('users')
                        ->where([['id','<>',7], ['token_app','<>',''], ['cod_grupo_empresarial','=', 1]])
                        ->pluck('token_app')->toArray();
        
        $datas = [
                    'channelName' => 'grupo_7',
                    'tokens' =>  $dataUserTokens, 
                    'bodyMessage' => 'Hey!! '.$nombre_usu.' acaba de hacer una publicación, mira que dice!',
                ];
     
        $this->notificacion($datas);    
    }

    public function notificacionApi(Request $request)
    {
        $datas = [
            'channelName' => $request->channelName,
            'tokens' =>  $request->tokens,
            'bodyMessage' => $request->bodyMessage,
        ];

        $this->notificacion($datas);
    }

    public function notificacion($request)
    { 
        $channelName    = $request['channelName'];
        $tokens         = $request['tokens'];
        $bodyMessage    = $request['bodyMessage']; 

        try{
        
            if(count($tokens) > 0)
            {
                // You can quickly bootup an expo instance
                $expo =  Expo::normalSetup();  

                // Subscribe the recipient to the server
                for($x =0; $x < count($tokens); $x++)
                {
                    $recipient  = $tokens[$x];
                    $expo->subscribe($channelName, $recipient);
                }
                 
                // Build the notification data
                $notification = ['body' => $bodyMessage];
                
                // Notify an interest with a notification
                $expo->notify([$channelName], $notification); 

                for($x =0; $x < count($tokens); $x++)
                {
                    $recipient  = $tokens[$x];
                    $expo->unsubscribe($channelName, $recipient);
                } 

                return true;
            }else{
                return false;
            }

        }catch(Exception $e){
            return false;
        }
    }
}