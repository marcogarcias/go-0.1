<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Tag;
use App\Models\User;
use App\Models\Estado;
use App\Models\Section;
use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\PublicationTag;
use App\Models\PublicationGallery;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{

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
    $publications = Publication::whereNull('deleted_at')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    foreach($publications as $pub){
      $pub->hashPublication = Crypt::encryptString($pub->idPublication);
      $pub->md5Municipio = md5($pub->idPublication);
    }

    $sections = Section::where('deleted', 0)->get();
    //$municipios = Municipio::where('deleted', 0)->get();
    $tags = Tag::where('deleted', 0)->get();

    return view('site.admin.publications', compact('publications', 'tags', 'sections'));
  }

  /**
   * Obtiene un listado de publicaciones o una publicación dada su id
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getPublications(Request $req)
  {
    $res=array('success'=>false, 'action'=>'list');
    if($req->ajax()){

      $data = $req->input('data');
      $idPublication = $data['hashPublication'] ? $data['hashPublication'] : 0;   
      $idPublication = $idPublication ? Crypt::decryptString($idPublication) : 0;

      $publications = Publication::whereNull('deleted_at')
        ->with(['gallery', 'municipio.estado', 'tags'])
        ->orderBy('created_at', 'desc');

      if($publications){
        $publications = $publications->where('idPublication', $idPublication);
      }

      $publications = $publications->paginate(10);

      foreach($publications as $publication){
        $publication->description = str_replace('<br />', '', $publication->description);
      }


      $res['success'] = true;
      $res['type'] = 'success';
      $res['message'] = 'Listado de publicaciones';
      $res['data'] = $publications;

      $res = response()->json($res, 200);
    }
    return $res;
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
  public function store(Request $req)
  {
    $res=array('success'=>false, 'action'=>'save');
    if($req->ajax()){

      $filename='';
      $galleryDel = [];

      $order = 1;

      $idPub = $req->input('hashPublication');
      $idPub = $idPub ? Crypt::decryptString($idPub) : null;
      
      if($idPub){
        $publication = Publication::find($idPub);
      }else{
        $publication = new Publication();
      }

      $publication->title = $req->input('title');
      $publication->subtitle = $req->input('subtitle');
      $publication->pseudonym = $req->input('pseudonym') ? $req->input('pseudonym') : auth()->user()->name;
      $publication->datetime = $req->input('datetime') ? $req->input('datetime') : date('Y-m-d H:i:s');
      $publication->synopsis = htmlspecialchars($req->input('synopsis'));
      $publication->description = nl2br(htmlspecialchars($req->input('description')));
      $publication->price = $req->input('price');
      $publication->address = $req->input('address');
      $publication->lat = $req->input('lat');
      $publication->lng = $req->input('lng');
      $publication->facebook = $req->input('facebook');
      $publication->instagram = $req->input('instagram');
      $publication->twitter = $req->input('twitter');
      $publication->youtube = $req->input('youtube');
      $publication->web = $req->input('web');
      $publication->disabled = $req->input('disabled');
      $publication->municipio_id = $req->input('municipio') ? Crypt::decryptString($req->input('municipio')) : null;
      $publication->section_id = $req->input('section') ? Crypt::decryptString($req->input('section')) : null;
      $publication->visits = $req->input('visits') ? $req->input('visits') : 0;
      $publication->likes = $req->input('likes') ? $req->input('likes') : 0;
      $publication->user_id = auth()->id();
      $tags = $req->input('tags');

      if($req->hasFile('portada')){
        $pathImageAr = makeDir("publications/frontPage");
        $pathImageAbs = isset($pathImageAr["absolute"]) ? $pathImageAr["absolute"] : "";
        $pathImageRel = isset($pathImageAr["relative"]) ? $pathImageAr["relative"] : "";
        $pathImageRel = rtrim($pathImageRel, '/');
        //$filename = time().".png";
        //$file = $pathImageAbs.$filename;
        //$file = $pathImageRel.$filename;
        $file = $pathImageRel;
        $filename = $req->portada->store($file, 'public');
        $publication->image = $filename;
      }

      $pub = $publication->save();

      if($pub){
        if(is_array($tags) && count($tags)){
          // eliminar de forma lógica todas las subsecciones/tags que tiene este registro
          PublicationTag::
            whereNull('deleted_at')
            ->where('publication_id', $publication->idPublication)
            ->update(['deleted_at'=>date('Y-m-d H:i:s')]);

          // agregar o actualizar las subsecciones/tags
          foreach ($tags as $tag) {
            $tag = Crypt::decryptString($tag);
            PublicationTag::updateOrCreate(
              ['publication_id'=>$publication->idPublication, 'tag_id'=>$tag],
              [ 'deleted_at'=>null ]
            );
          }
        }

        if($req->hasFile('gallery')){
          $pathImageAr = makeDir("publications/gallery");
          $pathImageAbs = isset($pathImageAr["absolute"]) ? $pathImageAr["absolute"] : "";
          $pathImageRel = isset($pathImageAr["relative"]) ? $pathImageAr["relative"] : "";
          $pathImageRel = rtrim($pathImageRel, '/');
          $file = $pathImageRel;
          foreach($req->file('gallery') as $image) {
            $publicationGallery = new PublicationGallery();
            $filenameGallery = $image->store($file, 'public');
            $publicationGallery->path = $pathImageRel;
            $publicationGallery->image = basename($filenameGallery);
            $publicationGallery->order = $order++;
            $publicationGallery->publication_id = $publication->idPublication;
            $publicationGallery->save();
          }
        }

        if($req->input('galleryDel')){
          $galleryDel = $req->input('galleryDel');
          foreach($galleryDel as $idx => $item){
            $galleryDel[$idx] = Crypt::decryptString($item);
          }
          $gallery = $publication->gallery;
          $deletedCount = PublicationGallery::whereIn('id', $galleryDel)->update(['deleted_at'=>date('Y-m-d H:i:s')]);

          foreach($galleryDel as $hash){
            foreach($gallery as $gal){
              if($hash == Crypt::decryptString($gal->hashGallery)){
                var_dump("borrando: ", asset('storage/' . $gal->path . '/' . $gal->image));
                var_dump("borrando: ", Storage::url($gal->path . '/' . $gal->image));
                Storage::delete(Storage::url($gal->path . '/' . $gal->image));
                continue;
              }
            }
          }
        }
        die('...');

        $res['success'] = true;
        $res['type'] = 'success';
        $res['message'] = 'Publicación creada correctamente';
      }else{
        $res['success'] = false;
        $res['type'] = 'error';
        $res['message'] = 'Publicación creada correctamente';
      }
      $res = response()->json($res, 200);
    }
    return $res;
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
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getEstados(Request $req)
  {
    $res=array('success'=>false, 'action'=>'load');
    if($req->ajax()){

      $estados = Estado::
        where('deleted', 0)
        ->orderBy('name', 'desc')
        ->get();
      /*foreach($estados as $estado){
        $estado->hashEstado = Crypt::encryptString($estado->idestado);
        $estado->md5Estado = md5($estado->idestado);
      }*/

      $res['success'] = true;
      $res['message'] = 'Listado de Estados';
      $res['data'] = $estados;
      
      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Obtiene el listado de municipios de un estado en especifico
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getMunicipios(Request $req)
  {
    $res=array('success'=>false, 'action'=>'load');
    if($req->ajax()){

      $data = $req->input('data');

      $estado_id = $data['hashEstado'] ? $data['hashEstado'] : null;
      $estado_id = $estado_id ? Crypt::decryptString($estado_id) : null;
      $municipios = Municipio::
        where('estado_id', $estado_id)
        ->where('deleted', 0)
        ->orderBy('name', 'asc')
        ->get();
      /*foreach($municipios as $municipio){
        $municipio->hashMunicipio = Crypt::encryptString($municipio->idmunicipio);
        $municipio->md5Municipio = md5($municipio->idestado);
      }*/

      $res['success'] = true;
      $res['message'] = 'Listado de Estados';
      $res['data'] = $municipios;
      
      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Obtiene el listado de las secciones
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getSections(Request $req)
  {
    $res=array('success'=>false, 'action'=>'load');
    if($req->ajax()){

      $data = $req->input('data');

      $section_id = $data['hashSection'] ? $data['hashSection'] : null;
      $section_id = $section_id ? Crypt::decryptString($section_id) : null;
      $sections = Section::where('deleted', 0)
        ->orderBy('name', 'asc')
        ->get();
      /*foreach($sections as $section){
        $section->hashSection = Crypt::encryptString($section->idsection);
        $section->md5Section = md5($section->idsection);
      }*/

      $res['success'] = true;
      $res['message'] = 'Listado de secciones';
      $res['data'] = $sections;
      
      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Obtiene el listado de las secciones
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getTags(Request $req)
  {
    $res=array('success'=>false, 'action'=>'load');
    if($req->ajax()){

      $data = $req->input('data');

      $section_id = $data['sec'] ? $data['sec'] : null;
      $section_id = $section_id ? Crypt::decryptString($section_id) : null;
      $tags = Tag::where('section_id', $section_id)
        ->where('deleted', 0)->get();

      /*foreach($tags as $tag){
        $tag->hashTag = Crypt::encryptString($tag->idtag);
        $tag->md5Tag = md5($tag->idtag);
      }*/

      $res['success'] = true;
      $res['message'] = 'Listado de tags';
      $res['data'] = $tags;
      
      $res = response()->json($res, 200);
    }
    return $res;
  }
}
