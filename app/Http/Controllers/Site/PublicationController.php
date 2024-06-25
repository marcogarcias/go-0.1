<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

use App\Mail\Emails;
use App\Models\Publication;
use Illuminate\Http\Request;
use App\Models\PublicationTag;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class PublicationController extends Controller
{
  
  /**
   * Página  principal de publicaciones
   *
   * @return \Illuminate\Http\Response
   */
  public function index(){
    $publications = Publication::whereNull('deleted_at')
      ->with('gallery')
      ->orderBy('created_at', 'desc')
      ->paginate(10);
    return view('site.publications', compact('publications'));
  }

  /**
   * Detalle de la publicación
   *
   * @return \Illuminate\Http\Response
   */
  public function publication(Publication $pub){
    if(!is_object($pub))
      return redirect()->route("home");
    // obteniendo la galería
    $pub->load('gallery');

    
    //dd($pub->gallery, $pub);

    $pub->visits += 1;
    $pub->save();
    //$stablish->hashStab = Crypt::encryptString($stablish['idstablishment']);
    return view("site.publication", compact("pub"));
  }

  /**
   * Agrega un like a la publicación
   *
   * @return \Illuminate\Http\Response
   */
  public function setLike(Request $req){
    $res=array('success'=>false);
    if($req->ajax()){

      $data = $req->input('data');
      $idPublication = isset($data['hashPublication']) && $data['hashPublication'] ? $data['hashPublication'] : 0;
      $idPublication = $idPublication ? Crypt::decryptString($idPublication) : 0;

      if($idPublication){
        $pub = Publication::find($idPublication);
        $pub->likes += 1;
        $pub->save();

        $res['success'] = true;
        $res['type'] = 'success';
        $res['message'] = 'Set like';
        $res['data'] = ['likes'=>$pub->likes];
      }else{
        $res['type'] = 'warning';
        $res['message'] = 'No se recibió ningún hash';
      }
      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Envia el formulario de contacto por correo y lo guarda en la base de datos
   *
   * @return \Illuminate\Http\Response
   */
  public function sendContact(Request $req){
    $res=array('success'=>false);
    if($req->ajax()){

      $data = $req->input('data');

      $dataEmail = [
        'name' => isset($data['name']) && $data['name'] ? $data['name'] : null,
        'email' => isset($data['email']) && $data['email'] ? $data['email'] : null,
        'message' => isset($data['message']) && $data['message'] ? $data['message'] : null,
        'url' => 'https://ejemplo.com'
      ];

      try{
        $resEmail = Mail::to($dataEmail['email'])->send(new Emails($dataEmail));
        $res['success'] = true;
        $res['type'] = 'success';
        $res['message'] = 'El formulario ha sido enviado correctamente.';
        $res['data'] = ['resEmail'=>$resEmail];
        return response()->json($res, 200);
      }catch(Exception $e){
        $res['type'] = 'warning';
        $res['message'] = 'No se envió correctamente el formulario. Error: '.$e->getMessage() ;
        return response()->json($res, 200);
      }
    }
  }
}
