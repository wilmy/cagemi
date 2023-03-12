<?php
 
namespace App\Http\Controllers\Notifications;
  
use ExponentPhpSDK\Expo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class NewNotifications extends Controller
{
    public function index()
    {
        $dataUser = DB::table('users')->find(5); 
        $nombre_usu = $dataUser->name;

        $list_token = array(); 
        $dataUserTokens = DB::table('users')
                        ->where([['token_app','<>',''], ['cod_grupo_empresarial','=', 1]])
                        ->pluck('token_app')->toArray();

        $datas = [
                    'channelName' => 'grupos',
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

                return true;
            }else{
                return false;
            }

        }catch(Exception $e){
            return false;
        }
    }
}