<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class ChatController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }

  /**
   * Recupera todos los mensajes de un usuario enviados a un establecimiento.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function loadAllMessages(Request $req)
  {
    $res=array('success'=>false);
    if($req->ajax()){
      $data = $req->input('data');
      $type = isset($data['type']) && $data['type'] ? $data['type'] : 0;
      $userClient = isset($data['userClient']) && $data['userClient'] ? Crypt::decryptString($data['userClient']) : 0;
      $userStablishment = isset($data['userStablishment']) && $data['userStablishment'] ? Crypt::decryptString($data['userStablishment']) : 0;
      //DB::enableQueryLog();
      ChatMessage::where('deleted', 0)
        ->where('userclient_id', $userClient)
        ->where('userstablishment_id', $userStablishment)
        ->update(['viewed' => 1]);

      $messages = ChatMessage::select('c.idchat', 'c.message', 'c.viewed', 'c.from')
        ->from('chat_messages AS c')
        ->join('users AS uc', 'uc.id', '=', 'c.userclient_id')
        ->join('users AS us', 'us.id', '=', 'c.userstablishment_id')
        ->where('c.deleted', 0)
        ->where('uc.deleted', 0)
        ->where('us.deleted', 0)
        ->where('c.userclient_id', $userClient)
        ->where('c.userstablishment_id', $userStablishment)
        ->orderBy('c.idchat', 'ASC')
        ->get();
      //dd($userClient, $userStablishment, $messages);
      //dd(DB::getQueryLog());
      if($messages){
        $res = response()->json(array('success'=>true, 'result'=>$messages), 200);
      }else{
        $res = response()->json(array('success'=>false, 'result'=>'El mensaje no pudo ser entregado.'), 200);
      }
    }
    return $res;
  }

  /**
   * Recupera los mensajes que ha mandado un establecimiento.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function loadMessages(Request $req)
  {
    $res=array('success'=>false);
    if($req->ajax()){
      $data = $req->input('data');
      $type = isset($data['type']) && $data['type'] ? $data['type'] : 0;
      $receivedMsg = isset($data['receivedMsg']) && $data['receivedMsg'] ? $data['receivedMsg'] : 0;
      $userClient = isset($data['userClient']) && $data['userClient'] ? Crypt::decryptString($data['userClient']) : 0;
      $userStablishment = isset($data['userStablishment']) && $data['userStablishment'] ? Crypt::decryptString($data['userStablishment']) : 0;
      $messagesCount=0;

      //DB::enableQueryLog();
      $messages = ChatMessage::select('c.idChat', 'c.message', 'c.viewed', 'c.from')
        ->from('chat_messages AS c')
        ->join('users AS uc', 'uc.id', '=', 'c.userclient_id')
        ->join('users AS us', 'us.id', '=', 'c.userstablishment_id')
        ->where('c.deleted', 0)
        ->where('uc.deleted', 0)
        ->where('us.deleted', 0)
        ->where('c.userclient_id', $userClient)
        ->where('c.userstablishment_id', $userStablishment);

      if($type == 'clientToStablishment'){
        $messages = $messages->where('c.from', 'stablishment');
      }else{
        $messages = $messages->where('c.from', 'client');
      }
      $messagesCount = $messages->orderBy('c.idchat', 'DESC')->count();
      $messages = $messages->orderBy('c.idchat', 'DESC')
        ->take($messagesCount-$receivedMsg)
        ->get();

      // marcandop como visto el mensaje(s) nuevo(s)
      foreach ($messages as $msg) {
        $msg->where('viewed', 0)
          ->where('idchat', $msg->idChat)
          ->update(['viewed' => 1]);
      }

      //dd($userClient, $userStablishment, $messages);
      //dd(DB::getQueryLog());
      $res = response()->json(array('success'=>true, 'result'=>$messages), 200);
    }
    return $res;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function messageSave(Request $req)
  {
    $res=array('success'=>false);
    if($req->ajax()){
      $data = $req->input('data');
      $message = isset($data['message']) && $data['message'] ? $data['message'] : '';
      $from = isset($data['from']) && $data['from'] ? $data['from'] : '';
      $idUserClient = isset($data['userClient']) && $data['userClient'] ? Crypt::decryptString($data['userClient']) : 0;
      $idUserStablishment = isset($data['userStablishment']) && $data['userStablishment'] ? Crypt::decryptString($data['userStablishment']) : 0;
      $messageObj = ChatMessage::create([
        'message'=>$message,
        'from'=>$from,
        'userclient_id'=>$idUserClient,
        'userstablishment_id'=>$idUserStablishment,
      ]);

      if($messageObj){
        $res = response()->json(array('success'=>true, 'result'=>$messageObj), 200);
      }else{
        $res = response()->json(array('success'=>false, 'result'=>'El mensaje no pudo ser entregado.'), 200);
      }
    }
    return $res;
  }

  /**
   * Recupera todos los usuarios que han mandado un mensaje al establecimiento
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function loadAllUsers(Request $req)
  {
    $res=array('success'=>false);
    if($req->ajax()){
      $data = $req->input('data');
      $userStablishment  = isset($data['userStablishment']) && $data['userStablishment'] ? Crypt::decryptString($data['userStablishment']) : 0;
      //DB::enableQueryLog()
      $users = ChatMessage::select('uc.name', 'uc.email', 'us.id AS userStablishment', 'uc.id AS userClient')
        ->from('chat_messages AS c')
        ->join('users AS uc', 'uc.id', '=', 'c.userclient_id')
        ->join('users AS us', 'us.id', '=', 'c.userstablishment_id')
        ->where('c.deleted', 0)
        ->where('uc.deleted', 0)
        ->where('us.deleted', 0)
        ->where('c.userstablishment_id', $userStablishment)
        ->orderBy('c.idchat', 'ASC')
        ->groupBy('uc.name', 'uc.email', 'userStablishment', 'userClient')
        ->get();
      //dd(DB::getQueryLog());
      foreach ($users as $usr) {
        $msgNew = ChatMessage::
          where('viewed', 0)
          ->where('deleted', 0)
          ->where('userclient_id', $usr->userClient)
          ->count();

        $usr->userStablishment = Crypt::encryptString($usr->userStablishment);
        $usr->userClient = Crypt::encryptString($usr->userClient);
        $usr->msgNew = $msgNew;
      }

      if($users){
        $res = response()->json(array('success'=>true, 'result'=>$users), 200);
      }else{
        $res = response()->json(array('success'=>false, 'result'=>'Sin usuarios.'), 200);
      }
    }
    return $res;
  }

  /**
   * Obtiene la cantidad de mensajes no leidos
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function loadNewMsgGeneral(Request $req)
  {
    $res=array('success'=>false);
    $newMessages = 0;
    if($req->ajax()){
      $data = $req->input('data');
      $type = isset($data['type']) && $data['type'] ? $data['type'] : 0;
      $userClient = isset($data['userClient']) && $data['userClient'] ? Crypt::decryptString($data['userClient']) : 0;
      $userStablishment = isset($data['userStablishment']) && $data['userStablishment'] ? Crypt::decryptString($data['userStablishment']) : 0;
      
      //DB::enableQueryLog();
      $newMessages = ChatMessage::select('c.idchat AS newMessages')
        ->from('chat_messages AS c')
        ->where('c.deleted', 0)
        ->where('c.viewed', 0)
        ->where('c.userstablishment_id', $userStablishment);

      if($type == 'clientToStablishment')
        $newMessages = $newMessages->where('c.userclient_id', $userClient);

      $newMessages = $newMessages->count();
      //dd(DB::getQueryLog());
      $res = response()->json(array('success'=>true, 'result'=>$newMessages), 200);
    }
    return $res;
  }
}
