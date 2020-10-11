<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

   use Auth;
   use App\Models\Twilio;
   use Twilio\Rest\Client;
   use Twilio\Jwt\AccessToken;
   use Illuminate\Support\Str;
   use Twilio\Jwt\Grants\VideoGrant; 

class TwilioController extends Controller
{
    protected $sid;
    protected $token;
    protected $key;
    protected $secret;


    public function __construct()
    {
       $this->sid = config('services.twilio.sid');
       $this->token = config('services.twilio.token');
       $this->key = config('services.twilio.key');
       $this->secret = config('services.twilio.secret');
    }


    /*
    |------------------------------------------------------------------
    |   Video Rooms Index
    |------------------------------------------------------------------
    */

    public function index()
    {
       
       $rooms = Twilio::get();

       /*$rooms = [];
       try {
           $client = new Client($this->sid, $this->token);
           $allRooms = $client->video->rooms->read([]);

            $rooms = array_map(function($room) {
               return $room->uniqueName;
            }, $allRooms);

       } catch (Exception $e) {
           echo "Error: " . $e->getMessage();
       }*/

       return view('twilio.index', ['rooms' => $rooms]);
    }


    /*
    |------------------------------------------------------------------
    |   Video Rooms Create
    |------------------------------------------------------------------
    */
    public function createRoom(Request $request)
    {
       $client = new Client($this->sid, $this->token);

       $v_session = new Twilio;

       $v_session->session_name = 'session_'.uniqid().rand(99,1000);
       $v_session->session_pass =  Str::uuid();
       $v_session->save();


           $client->video->rooms->create([
               'uniqueName' => $v_session->session_name,
               'type' => 'group',
               'recordParticipantsOnConnect' => false
           ]);

           \Log::debug("Session Created: ".$v_session->session_name);
       

          return redirect()->action('TwilioController@index')->with('room_session_created',[
           'roomLink' => url('/twilio').'/join/'.$v_session->session_name,
           'roomPass' => $v_session->session_pass,
        ]);
    }


    /*
    |------------------------------------------------------------------
    |   Join Video Session
    |------------------------------------------------------------------
    */
    public function joinRoom(Request $request, $roomName)
    {

       $identity = 'tw_'.uniqid();
       $v_session = Twilio::where('session_name', $roomName)->firstOrFail();

       if(Twilio::where('session_name', $roomName)->count() > 0){


        if($request->get('s_pass')){

            if($request->get('s_pass') ==  $v_session->session_pass){

                \Log::debug("joined with identity: $identity");
                $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);
                $videoGrant = new VideoGrant();
                $videoGrant->setRoom($roomName);
                $token->addGrant($videoGrant);
                return view('twilio.room', [ 'accessToken' => $token->toJWT(), 'roomName' => $roomName ])->with('action_success','Room Joined Successfully.');
            }

            else{

                return view('twilio.index2', [
           's_enter' => 'Enter Room Pass to continue.',
           's_invalid' => 'Room pass invalid.',
           'roomName'  => $v_session->session_name,
        ]);
            }
         }

        else{

                return view('twilio.index2', [
           's_enter' => 'Enter Room Pass to continue.',
           's_invalid' => '',
           'roomName'  => $v_session->session_name,
        ]);
       }    
            
      }

        return view('twilio.index')->with('fail_message', 'Room does not exist.');

       
    }
}
