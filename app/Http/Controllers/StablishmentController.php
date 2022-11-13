<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use App\Models\Zone;
use App\Models\Section;
use App\Models\Estado;
use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Models\Stablishment;
use App\Models\StablishmentTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class StablishmentController extends Controller
{
  private $pathImage = "img/site/stablishments/logos/";
  private $pathSummary = "img/site/stablishments/summary";

  public function __construct(){
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $stabs = Stablishment::select(
      's.idstablishment', 's.name', 's.description', 's.direction', 's.image', 's.phone', 's.whatsapp',
      's.facebook', 's.instagram', 's.twitter', 's.youtube', 's.hour', 's.offer', 's.disabled', 's.disabledGlobal', 's.created_at',
      'sec.name AS section', 'z.name AS zone')
      ->from('stablishments AS s')
      ->join('zones AS z', 'z.idzone', '=', 's.zone_id')
      ->join('municipios AS m', 'm.idmunicipio', '=', 's.municipio_id')
      ->join('estados AS e', 'e.idestado', '=', 'm.estado_id')
      ->join('sections AS sec', 'sec.idsection', '=', 's.section_id')
      //->join('stablishments_tags AS st', 'st.stablishment_id', '=', 'stablishments.idstablishment')
      //->join('tags AS t', 't.idtag', '=', 'st.tag_id')
      ->where('s.deleted', 0)
      ->orderBy('created_at', 'desc')
      ->get();
    $sections = Section::where('deleted', 0)->get();
    $zones = Zone::where('deleted', 0)->get();
    $tags = Tag::where('deleted', 0)->get();
    return view('site.admin.stablishments', compact('stabs', 'zones', 'tags', 'sections'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $sections = Section::where('deleted', 0)->get();
    $zones = Zone::where('deleted', 0)->get();
    $tags = Tag::where('deleted', 0)->get();
    $users = User::select('usr.id', 'usr.email', 'stab.user_id')
      ->from('users AS usr')
      ->leftjoin('stablishments AS stab', 'stab.user_id', '=', 'usr.id')
      ->where('usr.deleted', 0)
      ->where('email', 'LIKE', '%@somosgo%')
      ->whereNull('stab.user_id')
      ->get();

    return view('site.admin.stablishmentCreate', compact('sections', 'zones', 'tags', 'users'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $image;
    $imageName='default.png';
    
    $summary;
    $summaryName='default.png';

    $idStab;
    $tags;
    $facebook = $instagram = $twitter = $youtube = '';

    request()->validate([
      'nombre'=>'required|min:1|max:100',
      'descripcion'=>'required|min:10|max:255',
      'direccion'=>'required|min:10|max:255',
      'latitud'=>'required|min:5|max:255',
      'longitud'=>'required|min:5|max:255',
      'logotipo' => "image|mimes:jpeg,png|max:200|dimensions:min_width=90,min_height=55,max_width=110,max_height=75",
      'resumen' => "image|mimes:jpeg,png|max:500|dimensions:min_width=890,min_height=950,max_width=910,max_height=970",
      'telefono'=>'max:15',
      'whatsapp'=>'max:15',
      'facebook'=>'max:255',
      'instagram'=>'max:255',
      'twitter'=>'max:255',
      'youtube'=>'max:255',
      'horario'=>'required|min:5|max:255',
      'expiracion'=>'required|min:10|max:10',
      'zona'=>'required',
      'section'=>'required',
      'visitas'=>'numeric'
    ]);

    if($request->hasFile("logotipo")){
      $imageName = str_replace(' ', '_', request('nombre')).'-logo.png';
      $image = $request->file("logotipo");
      $image->move($this->pathImage, $imageName);
    }

    if($request->hasFile("resumen")){
      $summaryName = str_replace(' ', '_', request('nombre')).'-summary.png';
      $summary = $request->file("resumen");
      $summary->move($this->pathSummary, $summaryName);
    }

    if(request('facebook'))
      $facebook = str_replace(array('http://', 'https://'), '', request('facebook'));
    
    if(request('instagram'))
      $instagram = str_replace(array('http://', 'https://'), '', request('instagram'));

    if(request('youtube'))
      $youtube = str_replace(array('http://', 'https://'), '', request('youtube'));

    if(request('twitter'))
      $twitter = str_replace(array('http://', 'https://'), '', request('twitter'));

    $stab = Stablishment::create([
      'name'=>request('nombre'),
      'description'=>request('descripcion'),
      'description2'=>request('descripcion2'),
      'direction'=>request('direccion'),
      'lat'=>request('latitud'),
      'lng'=>request('longitud'),
      'image'=>$imageName,
      'summary'=>$summaryName,
      'phone'=>request('telefono'),
      'whatsapp'=>request('whatsapp'),
      'facebook'=>$facebook,
      'instagram'=>$instagram,
      'twitter'=>$twitter,
      'youtube'=>$youtube,
      'hour'=>request('horario'),
      'offer'=>request('oferta')?1:0,
      'disabled'=>request('deshabilitado')?1:0,
      'expiration'=>request('expiracion'),
      'municipio_id'=>1,
      'zone_id'=>request('zona'),
      'section_id'=>request('section'),
      'user_id'=>request('usuario'),
      'range'=>request('visitas')
    ]);

    if(isset($stab->idstablishment) && $stab->idstablishment){
      $idStab = $stab->idstablishment;
      $tags = request('tags');

      if(is_array($tags)){
        foreach ($tags as $tag) {
          StablishmentTag::create([
            'stablishment_id'=>$idStab,
            'tag_id'=>$tag
          ]);
        }
      }
    }
    return redirect()->route('admin.stablishments');
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
  public function edit(Stablishment $stab)
  {
    $stab_tags = [];
    $stab_tags_ = StablishmentTag::where('stablishment_id', $stab->idstablishment)
    ->where('deleted', 0)
    ->get();
    foreach ($stab_tags_ as $tag)
      $stab_tags[] = $tag->tag_id;

    $zones = Zone::where('deleted', 0)->get();
    $sections = Section::where('deleted', 0)->get();
    $tags = Tag::where('section_id', $stab->section_id)->where('deleted', 0)->get();
    $users = User::select('usr.id', 'usr.email', 'stab.user_id')
      ->from('users AS usr')
      ->leftjoin('stablishments AS stab', 'stab.user_id', '=', 'usr.id')
      ->where('usr.deleted', 0)
      ->where('email', 'LIKE', '%@somosgo%')
      ->whereNull('stab.user_id')
      ->orWhere('stab.user_id', $stab->user_id)
      ->get();

    return view('site.admin.stablishmentEdit', compact('sections', 'zones', 'tags', 'stab', 'stab_tags', 'users'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Stablishment $stab)
  {
    $image;
    $imageName='default.png';
    
    $summary;
    $summaryName='default.png';

    $idStab;
    $tags=array();
    $facebook = $instagram = $twitter = $youtube = '';
    // comprobar si el establecimiento se llama igual o cambio de nombre. Si cambio, hay que eliminar el logotigo y el summary
    $stab_ = Stablishment::find(request('stab'));
    $imageName = $stab_->image;
    $imageNameUrl = '';
    $summaryName = $stab_->summary;
    $summaryNameUrl = '';

    if(is_object($stab_)){
      if(request("logotipo") && $stab_->image!='default.png'){
        $imageNameUrl = public_path().'/img/site/stablishments/logos/'.$stab_->image;
        is_file($imageNameUrl) && unlink($imageNameUrl);
      }
      if(request("resumen") && $stab_->summary!='default.png'){
        $summaryNameUrl = public_path().'/img/site/stablishments/summary/'.$stab_->summary;
        is_file($summaryNameUrl) && unlink($summaryNameUrl);
      }
    }

    request()->validate([
      'nombre'=>'required|min:1|max:100',
      'descripcion'=>'required|min:10|max:255',
      'direccion'=>'required|min:10|max:255',
      'latitud'=>'required|min:5|max:255',
      'longitud'=>'required|min:5|max:255',
      'logotipo' => "image|mimes:jpeg,png|max:200|dimensions:min_width=90,min_height=55,max_width=110,max_height=75",
      'resumen' => "image|mimes:jpeg,png|max:500|dimensions:min_width=890,min_height=950,max_width=910,max_height=970",
      'telefono'=>'max:15',
      'whatsapp'=>'max:15',
      'facebook'=>'max:255',
      'instagram'=>'max:255',
      'twitter'=>'max:255',
      'youtube'=>'max:255',
      'horario'=>'required|min:5|max:255',
      'expiracion'=>'required|min:10|max:10',
      'zona'=>'required',
      'section'=>'required',
      'visitas'=>'numeric',
    ]);

    if(request('logotipo')){
      $imageName = str_replace(' ', '_', request('nombre')).'-logo.png';
      $image = request('logotipo');
      $image->move($this->pathImage, $imageName);
    }

    if(request('resumen')){
      $summaryName = str_replace(' ', '_', request('nombre')).'-summary.png';
      $summary = request('resumen');
      $summary->move($this->pathSummary, $summaryName);
    }

    if(request('facebook'))
      $facebook = str_replace(array('http://', 'https://'), '', request('facebook'));
    
    if(request('instagram'))
      $instagram = str_replace(array('http://', 'https://'), '', request('instagram'));

    if(request('youtube'))
      $youtube = str_replace(array('http://', 'https://'), '', request('youtube'));

    if(request('twitter'))
      $twitter = str_replace(array('http://', 'https://'), '', request('twitter'));

    $stab->update([
      'name'=>request('nombre'),
      'description'=>request('descripcion'),
      'description2'=>request('descripcion2'),
      'direction'=>request('direccion'),
      'lat'=>request('latitud'),
      'lng'=>request('longitud'),
      'image'=>$imageName,
      'summary'=>$summaryName,
      'phone'=>request('telefono'),
      'whatsapp'=>request('whatsapp'),
      'facebook'=>$facebook,
      'instagram'=>$instagram,
      'twitter'=>$twitter,
      'youtube'=>$youtube,
      'hour'=>request('horario'),
      'offer'=>request('oferta')?1:0,
      'disabled'=>request('deshabilitado')?1:0,
      'expiration'=>request('expiracion'),
      'municipio_id'=>1,
      'zone_id'=>request('zona'),
      'section_id'=>request('section'),
      'user_id'=>request('usuario')?request('usuario'):0,
      'range'=>request('visitas')
    ]);

    if(isset($stab->idstablishment) && $stab->idstablishment && request('tags')){
      $idStab = $stab->idstablishment;
      $tags = request('tags')?request('tags'):$tags;

      // eliminar de forma lógica todas las subsecciones/tags que tiene este registro
      StablishmentTag::
      where('deleted', 0)
      ->where('stablishment_id', $idStab)
      ->update(['deleted'=>1]);
      
      if(is_array($tags)){
        // agregar o actualizar las subsecciones/tags
        foreach ($tags as $tag) {
          StablishmentTag::updateOrCreate(
            ['stablishment_id'=>$idStab, 'tag_id'=>$tag],
            [ 'deleted'=>0 ]
          );
        }
      }
    }
    return redirect()->route('admin.stablishments'); 
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Stablishment $stab)
  {
    $imageNameUrl;
    $summaryNameUrl;

    $stab->update([
      'deleted'=>1
    ]);

    StablishmentTag::
      where('deleted', 0)
      ->where('stablishment_id', $stab->idstablishment)
      ->update(['deleted'=>1]);

    if($stab->image!='default.png'){
      $imageNameUrl = public_path().'/img/site/stablishments/logos/'.$stab->image;
      is_file($imageNameUrl) && unlink($imageNameUrl);
    }

    if($stab->summary!='default.png'){
      $summaryNameUrl = public_path().'/img/site/stablishments/summary/'.$stab->summary;
      is_file($summaryNameUrl) && unlink($summaryNameUrl);
    }

    return redirect()->route('admin.stablishments');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroyAll(Request $req){
    $res=array('success'=>false);
    if($req->ajax()){
      $regs = $req->input('regs', 0);

      foreach ($regs as $reg) {
        Stablishment::find($reg)->update(['deleted'=>1]);
      }
      $res = response()->json(array('success'=>true), 200);
    }
    return $res;
  }

  /**
   * Agrega una visita a todos los establecimientos
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function addVisitsAll(Request $req){
    $res=array('success'=>false, 'ty'=>'error');
    if($req->ajax()){
      $visits = 0;
      $stabs = Stablishment::select(
        's.idstablishment', 's.name', 's.range')
        ->from('stablishments AS s')
        ->where('s.deleted', 0)
        ->get();

      foreach ($stabs as $stab) {
        $stab->update(['range'=>++$stab->range]);
      }
      $res = response()->json(array('success'=>true, 'msg'=>'Se han generado nuevas visitas.', 'ty'=>'success'), 200);
    }
    return $res;
  }

  /**
   * Habilita/deshabilita de forma global un establecimiento
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function enabledGlobalStab(Request $req){
    $res=array("success"=>false, "ty"=>"error");
    if($req->ajax()){
      $data = $req->input("data");
      $idStab = isset($data["hashStab"]) && $data["hashStab"] ? Crypt::decryptString($data["hashStab"]) : 0;
      $enabled = isset($data["enabled"]) && $data["enabled"] ? $data["enabled"] : false;

      $stab = Stablishment::
        where("idstablishment", $idStab)
        ->update(["disabledGlobal"=>$enabled?0:1]);

      if($stab){
        $msg = "Se ha ".($enabled?"habilidado":"deshabilitado")." el negocio.";
        $res = response()->json(array("success"=>true, "msg"=>$msg, "ty"=>"success"), 200);
      }else{
        $msg = "No se pudo ".($enabled?"habilitar":"deshabilitar")." el negocio. Intente más tarde.";
        $res = response()->json(array("success"=>false, "msg"=>$msg, "ty"=>"warning"), 200);
      }
    }
    return $res;
  }

  public function loadTags(Request $req){
    $res=array('success'=>false);
    if($req->ajax()){
      $data = $req->input('data');
      $sec = isset($data['sec']) && $data['sec'] ? $data['sec'] : 0;

      $tags = Tag::where('section_id', $sec)
        ->where('deleted', 0)
        ->get();

      $res = response()->json(array('success'=>true, 'tags'=>$tags), 200);
    }
    return $res;
  }

  public function loadTagsAndChecks(Request $req){
    $res=array('success'=>false);
    if($req->ajax()){
      $data = $req->input('data');
      $sec = isset($data['sec']) && $data['sec'] ? $data['sec'] : 0;
      $stab = isset($data['stab']) && $data['stab'] ? $data['stab'] : 0;

      $tags = Tag::where('section_id', $sec)
        ->where('deleted', 0)
        ->get();

      $stab_tags = [];
      $stab_tags_ = StablishmentTag::where('stablishment_id', $stab)
      ->where('deleted', 0)
      ->get();
      foreach ($stab_tags_ as $tag)
        $stab_tags[] = $tag->tag_id;

      $res = response()->json(array('success'=>true, 'tags'=>$tags, 'stab_tags'=>$stab_tags), 200);
    }
    return $res;
  }
}
