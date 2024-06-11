<?php

namespace App\Http\Controllers;

use File;
use Auth;
use App\Models\Tag;
use App\Models\Zone;
use App\Models\User;
use App\Models\Section;
use App\Models\MenuProduct;
use Illuminate\Http\Request;
use App\Models\Stablishment;
use App\Models\Advertisement;
use App\Models\MyStablishment;
use App\Models\UserJobProfile;
use App\Models\StablishmentAd;
use App\Models\StablishmentJob;
use App\Models\StablishmentTag;
use App\Models\StablishmentMenu;
use Illuminate\Support\Facades\DB;
use App\Models\stablishmentMenuPdf;
use App\Models\StablishmentGallery;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\StablishmentJobSubType;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Storage;

class SiteController extends Controller
{
  /**
   * Página  principal
   *
   * @return \Illuminate\Http\Response
   */
  public function index(){
    $sections = Section::where('deleted', 0)->get();
    $ads = Advertisement::where('deleted', 0)->get();
    return view('site.index', compact('sections', 'ads'));
  }

  /**
   * Términos y condiciones y aviso de privacidad
   *
   * @return \Illuminate\Http\Response
   */
  public function termsAndConditions(){
    return view('site.termsAndConditions');
  }

  /**
   * Lista los negocios de la sección elegida
   *
   * @return \Illuminate\Http\Response
   */
  public function stablishments($sec=null){
    if($sec){
      $stablish = Stablishment::select(
        "stab.idstablishment", "stab.name", "stab.description", 
        "stab.direction", "stab.image", "stab.offer", 
        "stab.disabled", "stab.disabledGlobal")
        ->from("stablishments AS stab")
        ->join("municipios AS m", "m.idmunicipio", "=", "stab.municipio_id")
        ->join("estados AS e", "e.idestado", "=", "m.estado_id")
        ->join("sections AS s", "s.idsection", "=", "stab.section_id")
        //->join('stablishments_tags AS st', 'st.stablishment_id', '=', 'stablishments.idstablishment')
        //->join('tags AS t', 't.idtag', '=', 'st.tag_id')
        ->where("s.idsection", $sec)
        ->where("stab.disabled", 0)
        ->where("stab.deleted", 0)
        ->where("m.deleted", 0)
        ->where("e.deleted", 0)
        ->where("s.deleted", 0)
        ->orderByRaw("RAND()")
        ->get();

      $zones = Zone::where('deleted', 0)->get();
      $tags = Tag::
        where('section_id', $sec)
        ->where('deleted', 0)
        ->orderBy('name', 'ASC')
        ->get();
      return view('site.stablishments', compact('stablish', 'zones', 'tags', 'sec'));
    }else{
      return redirect()->route('home');
    }
  }

  /**
   * Ubicación en mapa de los establecimientos
   *
   * @return \Illuminate\Http\Response
   */
  public function stablishmentsMap($stab = null){
    if($stab){
      $stab = intval($stab);

      $stab = Stablishment::select(
      's.idstablishment', 's.name', 's.description', 's.description2', 
      's.direction', 's.image', 's.offer', 's.lat', 's.lng',
      'sc.image AS secImage')
      ->from('stablishments AS s')
      ->join('sections AS sc', 'sc.idsection', '=', 's.section_id')
      ->where('s.idstablishment', $stab)
      ->where('s.disabled', 0)
      ->where('s.deleted', 0)
      ->first();
    }
    return view('site.stablishmentsMap', compact('stab'));
  }

  /**
   * Detalle del negocio elegido
   *
   * @return \Illuminate\Http\Response
   */
  public function stablishment($stab=0){
    //DB::enableQueryLog();
    $idUser = Auth::id() ? Auth::id() : 0;
    $menus_ = $menus = [];
    $menuFile;
    $stablish = Stablishment::select(
      "s.idstablishment", "s.name", "s.description", "s.direction", 
      "s.image", "s.summary", "s.whatsapp", "s.facebook", "s.range", 
      "s.enablechat", "s.instagram", "s.twitter", "s.youtube", "s.web",
      "s.lat", "s.lng", "s.user_id", "s.disabled", "s.disabledGlobal",
      "sec.idsection", "sec.image AS secImage")
      ->from("stablishments AS s")
      //'t.name AS tagName', 't.description AS tagDesc', 't.image AS tagImage')
      ->join("municipios AS m", "m.idmunicipio", "=", "s.municipio_id")
      ->join("estados AS e", "e.idestado", "=", "m.estado_id")
      ->join("sections AS sec", "sec.idsection", "=", "s.section_id")
      //->join('stablishments_tags AS st', 'st.stablishment_id', '=', 's.idstablishment')
      //->join('tags AS t', 't.idtag', '=', 'st.tag_id')
      ->where("s.idstablishment", $stab)
      //->where("s.disabled", 0)
      ->where("s.deleted", 0)
      ->where("m.deleted", 0)
      ->where("e.deleted", 0)
      ->where("sec.deleted", 0)
      ->first();

    // obteniendo los menus y sus productos
    $menus_ = StablishmentMenu::
        select("sm.idmenu", "sm.name", "sm.description", "mp.idproduct", "mp.name AS prodName", 
          "mp.description AS prodDesc", "mp.price", "mp.price_discount")
        ->from("stablishments_menus AS sm")
        ->join("menus_products AS mp", "sm.idmenu", "=", "mp.menu_id")
        ->where("sm.disabled", 0)
        ->where("mp.disabled", 0)
        ->where("sm.deleted", 0)
        ->where("mp.deleted", 0)
        ->where("sm.stablishment_id", $stab)
        ->get();

    foreach ($menus_ as $menu) {
      $hash = md5($menu->idmenu);
      $prod = [
        "name"=> $menu->prodName,
        "description"=> base64_encode($menu->prodDesc),
        "price"=> $menu->price,
        "priceDisc"=> $menu->price_discount,
        "hash"=> Crypt::encryptString($menu->idproduct)
      ];
      if(!isset($menus[$hash])){
        $menus[$hash] = [
          "menu"=>[
            "name"=>$menu->name, 
            "description"=>$menu->description
          ], 
          "products"=>[]
        ];
      }
      $menus[$hash]["products"][] = $prod;
    }

    // obteniendo menu en pdf
    $menuFile = stablishmentMenuPdf::
      where("disabled", 0)
      ->where("deleted", 0)
      ->where("stablishment_id", $stab)
      ->get();
    $menuFile = isset($menuFile[0]) ? $menuFile[0] : $menuFile;

    // obteniendo la galería     
    $gallery = StablishmentGallery::select("idgallery", "path", "image")
      ->where("deleted", 0)
      ->where("stablishment_id", $stab)
      ->get();

    if(!is_object($stablish))
      return redirect()->route("home");

    $stablish->range += 1;
    $stablish->save();
    $jobs = self::myJobs_($stablish["idstablishment"]);
    $ads = self::myAds_($stablish["idstablishment"]);
    $stablish->hashStab = Crypt::encryptString($stablish['idstablishment']);
    $adsBg = [
      toKey("Horarios") => "#a02514", 
      toKey("Dirección") => "#9e5914",
      toKey("Teléfonos") => "#9b9e14",
      toKey("Formas de pago") => "#209e14",
      toKey("Cita o reservación") => "#149b6e",
      toKey("Entrega a domicilio") => "#148b9b",
      toKey("Oferta") => "#14339b",
      toKey("Promoción") => "#6d149e"
    ];
    return view("site.stablishment", compact("idUser", "stablish", "menus", "menuFile", "jobs", "ads", "gallery", "adsBg"));
  }

  /**
   * Sección para ver las vacantes
   *
   * @return \Illuminate\Http\Response
   */
  public function stablishmentsJobs(){
    $jobs = Stablishment::select(
        "stab.idstablishment", "stab.name AS stabName", "stab.description AS stabDescription", 
        "stab.direction", "stab.image", "stab.direction", 
        "stab.phone", "stab.whatsapp", "stab.facebook", "stab.web",
        "sj.name AS jobName", "sj.description AS jobDescription")
        ->from("stablishments AS stab")
        ->join("stablishments_jobs AS sj", "sj.stablishment_id", "=", "stab.idstablishment")
        ->where("stab.disabled", 0)
        ->where("stab.disabledGlobal", 0)
        ->where("stab.deleted", 0)
        ->where("sj.deleted", 0)
        ->get();
    return view('site.stablishmentsJobs', compact('jobs'));
  }

  /**
   * Establecimientos que el usuario ha agregado
   *
   * @return \Illuminate\Http\Response
   */
  public function mySpace(){
    $this->middleware('auth');
    $mySpace;
    $myStab = $myJobs = $myAds = $sections = $zones = $tags = $menus = $adsType = [];
    $iAmStab = auth()->user()->stablishment;
    $chat = session('chatStablishment');
    $mySpace = MyStablishment::select(
      'm_stab.idmystablishment',
      'stab.idstablishment', 'stab.name', 'stab.description', 
      'stab.direction', 'stab.image', 'stab.offer', 'stab.user_id')
      //'t.name AS tagName', 't.description AS tagDesc', 't.image AS tagImage')
      ->from('my_stablishments AS m_stab')
      ->join('stablishments AS stab', 'stab.idstablishment', '=', 'm_stab.stablishment_id')
      ->join('municipios AS m', 'm.idmunicipio', '=', 'stab.municipio_id')
      ->join('estados AS e', 'e.idestado', '=', 'm.estado_id')
      ->join('sections AS s', 's.idsection', '=', 'stab.section_id')
      //->join('stablishments_tags AS st', 'st.stablishment_id', '=', 'stablishments.idstablishment')
      //->join('tags AS t', 't.idtag', '=', 'st.tag_id')
      ->where('m_stab.deleted', 0)
      ->where('stab.disabled', 0)
      ->where('stab.deleted', 0)
      ->where('m_stab.user_id', auth()->id())
      ->where('m.deleted', 0)
      ->where('e.deleted', 0)
      ->where('s.deleted', 0)
      ->orderByRaw('RAND()')
      ->get();

    // si el usuario que inició sesión es una empresa tambien
    if($iAmStab){
      $sections = Section::where('deleted', 0)->get();
      $zones = Zone::where('deleted', 0)->get();
      $tags = Tag::where('deleted', 0)->get();

      $myStab = Stablishment::select('stab.idstablishment', 
        'stab.name', 'stab.description', 'stab.image',
        'stab.user_id', 'stab.range', "stab.disabled")
        ->from('stablishments AS stab')
        ->where('stab.deleted', 0)
        ->where('stab.user_id', auth()->id())
        ->first(auth()->id());
      $myStab->hashStab = Crypt::encryptString($myStab['idstablishment']);

      if(is_object($myStab)){
        $menus = self::myMenus_($myStab['idstablishment']);
        $myJobs = self::myJobs_($myStab['idstablishment']);
        $myAds = self::myAds_($myStab['idstablishment']);
      }
      $adsType = [
        Crypt::encryptString("Horarios")=>"Horarios",
        Crypt::encryptString("Dirección")=>"Dirección",
        Crypt::encryptString("Teléfonos")=>"Teléfonos",
        Crypt::encryptString("Formas de pago")=>"Formas de pago",
        Crypt::encryptString("Cita o reservación")=>"Cita o reservación",
        Crypt::encryptString("Entrega a domicilio")=>"Entrega a domicilio",
        Crypt::encryptString("Oferta")=>"Oferta",
        Crypt::encryptString("Promoción")=>"Promoción"
      ];
    }
    return view('site.mySpace', compact('mySpace', 'myStab', 'myJobs', 'myAds', 'chat', 'iAmStab', 'sections', 'zones', 'tags', 'menus', 'adsType'));
  }

  /**
   * Carga los menus dados de alta
   *
   * @return \Illuminate\Http\Response
   */
  public function loadMenus(Request $req){
    $res=array('success'=>false, 'action'=>'load');
    if($req->ajax()){
      $idStab = Crypt::decryptString(session('idStablishment'));
      $data = $req->input('data');
      $hash = isset($data['hash']) && $data['hash'] ? $data['hash'] : false;

      $menus = StablishmentMenu::
        where('stablishment_id', $idStab)
        ->where('deleted', 0);

      $file = stablishmentMenuPdf::
        where('stablishment_id', $idStab)
        ->where('deleted', 0)
        ->orderBy('created_at', 'desc')
        ->get();

      if($hash){
        if(strpos($hash, 'hash') === false){
          $hash = Crypt::decryptString($hash);
          $menus = $menus->where('idmenu', $hash)->first();
          $menus->hash = Crypt::encryptString($menus->idmenu);
        }else{
          $menus = [];
        }
      }else{
        $menus = $menus->get();
        foreach ($menus as $menu) {
          $menu->hash = Crypt::encryptString($menu->idmenu);
          $menu->stablishment_id = Crypt::encryptString($menu->stablishment_id);
        }

        foreach ($file as $f) {
          $f->hash = Crypt::encryptString($f->idMenuPdf);
          unset($f->idMenuPdf);
        }
      }
      $res['success']=true;
      $res['code']='success';
      $res['menus']= ['manual'=>$menus, 'file'=>$file];

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Agregar un menú con sus productos a la empresa
   *
   * @return \Illuminate\Http\Response
   */
  public function addMenu(Request $req){
    $res=array('success'=>false, 'action'=>'add');
    if($req->ajax()){
      $idStab = $idMenu = $menu = $idProduct = $action = $validate = null;
      $prods = [];
      $idStab = Crypt::decryptString(session('idStablishment'));
      $data = $req->input('data');

      $idMenu = isset($data[0]) && $data[0]['name']=='hashMenu' ? $data[0]['value'] : '';
      $idMenu = strpos($idMenu, 'hash') === false ? Crypt::decryptString($idMenu) : null;

      if(isset($data[1]) && $data[1]["name"]=="menuDisable"){
        $menuDisable = false;
        $menuName = isset($data[2]) && $data[2]['name']=='menuName' ? $data[2]['value'] : '';
        $menuDescripcion = isset($data[3]) && $data[3]['name']=='menuDescripcion' ? $data[3]['value'] : '';
      }else{
        $menuDisable = true; 
        $menuName = isset($data[1]) && $data[1]['name']=='menuName' ? $data[1]['value'] : '';
        $menuDescripcion = isset($data[2]) && $data[2]['name']=='menuDescripcion' ? $data[2]['value'] : '';
      }

      // validando campos de nombre de menú y su descripción
      $validateRes = validate([
        'menuName' => [$menuName, 'required|min:2|max:50'],
        'menuDescripcion' => [$menuDescripcion, 'max:250']
      ]);

      if(empty($validateRes)){
        // agregando los productos a un array
        foreach ($data as $d) {
          if(strpos($d['name'], 'prod') !== false && $d['value']){
            $valA = explode('-', $d['name']);
            isset($prods[$valA[2]]) || $prods[$valA[2]] = [];
            $prods[$valA[2]][$valA[1]] = $d['value'];
          }
        }

        $menu = StablishmentMenu::updateOrCreate(
          ['idmenu' => $idMenu],
          [
            'name' => $menuName,
            'description' => $menuDescripcion,
            'disabled' => $menuDisable,
            'stablishment_id' => $idStab
          ]
        );

        foreach ($prods as $key => $prod) {
          $idProduct = strpos($key, 'hash') === false ? Crypt::decryptString($key) : null;

          MenuProduct::updateOrCreate(
            ['idproduct' => $idProduct],
            [
              'name' => $prod['name'],
              'price' => $prod['precio'],
              'description' => isset($prod['description']) ? $prod['description'] : '',
              'menu_id' => $menu->idmenu
            ]
          );
        }
      }

/*echo '<pre>';
var_dump($prods);
echo '</pre>';
die('...');*/
      if($menu){
        $action = $idMenu ? 'actualizado' : 'creado';
        $res['success']=true;
        $res['code']='success';
        $res['message']="Se ha {$action} el menú '{$menuName}'";
      }else{
        $action = $idMenu ? 'actualizar' : 'crear';
        $res['success']=false;
        $res['code']='warning';
        $res['message']="No se pudo {$action} el menú.";
        $res['validate']=$validateRes;
      }
      $res['action']= $idMenu ? 'upd' : 'add';

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Carga uno o todos los productos de un menú
   *
   * @return \Illuminate\Http\Response
   */
  public function loadProducts(Request $req){
    $res=array('success'=>false, 'action'=>'load');
    if($req->ajax()){
      //$idStab = Crypt::decryptString(session('idStablishment'));
      $data = $req->input('data');
      $hashProduct = isset($data['hashProduct']) && $data['hashProduct'] ? $data['hashProduct'] : false;
      $hash = isset($data['hashMenu']) && $data['hashMenu'] ? $data['hashMenu'] : false;

      $products = StablishmentMenu::
        select('mp.idproduct', 'mp.name', 'mp.description', 'mp.price', 'mp.price_discount', 'mp.disabled')
        ->from('stablishments_menus AS sm')
        ->join('menus_products AS mp', 'sm.idmenu', '=', 'mp.menu_id')
        ->where('sm.deleted', 0)
        ->where('mp.deleted', 0);

      if($hashProduct){
          $hashProduct = Crypt::decryptString($hashProduct);
          $products = $products
            ->where('mp.idproduct', $hashProduct)
            ->first();
          $products->hash = Crypt::encryptString($products->idproduct);
      }elseif($hash){
        if(strpos($hash, 'hash') === false){
          $hash = Crypt::decryptString($hash);
          $products = $products->where('mp.menu_id', $hash);
        }
        $products = $products->get();
        foreach ($products as $product) {
          $product->hash = Crypt::encryptString($product->idproduct);
        }
      }else{
        $products = [];
      }

      $res['success']=true;
      $res['code']='success';
      $res['products']= $products;

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Elimina un producto de un menú
   *
   * @return \Illuminate\Http\Response
   */
  public function delProduct(Request $req){
    $res=array('success'=>false, 'action'=>'load');
    if($req->ajax()){
      $data = $req->input('data');
      $hashProduct = isset($data['hashProduct']) && $data['hashProduct'] ? $data['hashProduct'] : false;
      $hashProduct = (strpos($hashProduct, 'hash') === false) ? Crypt::decryptString($hashProduct) : 0;

      $product = MenuProduct::where('idproduct', $hashProduct)->update(['deleted' => 1]);

      if($product){
        $res['success']=true;
        $res['code']='success';
        $res['message']= 'El producto ha sido eliminado.';
      }else{
        $res['success']=false;
        $res['code']='error';
        $res['message']= 'El producto no pudo ser eliminado.';
      }

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Sube un menu ya sea pdf o imagen.
   *
   * @return \Illuminate\Http\Response
   */
  public function addMenuObj(Request $req){
    $res=array('success'=>false, 'action'=>'load');
    $idStab;
    $stabMenuPdf = [];
    $fileRes; $fileReg; 
    $data = [];
    if($req->ajax()){
      $idStab = Crypt::decryptString(session('idStablishment'));
      $idMenuPdf = $req->input('hash');
      $idMenuPdf = $idMenuPdf ? Crypt::decryptString($req->input('hash')) : '';

      if($req->hasFile('files')){
        $file = $req->file('files');

        if($idMenuPdf){
          $fileReg = stablishmentMenuPdf::find($idMenuPdf);
          $data['hahs'] = Crypt::encryptString($idMenuPdf);
          $data['path'] = $pathImageRel = $fileReg->path;
          $data['pdf'] = $filename = $fileReg->pdf;
          $dir = $pathImageRel.$filename;
          $stabMenuPdf['pdf'] = $filename;
          $fileRes = $fileReg->update($stabMenuPdf);
          $res['action'] = 'update';
        }else{
          $pathImageAr = makeDir('menus');
          $pathImageAbs = isset($pathImageAr["absolute"]) ? $pathImageAr["absolute"] : "";
          $pathImageRel = isset($pathImageAr["relative"]) ? $pathImageAr["relative"] : "";
          $filename =  time() .'.' . $file->getClientOriginalExtension();
          $dir = $pathImageRel.$filename;
          $stabMenuPdf['path'] = $pathImageRel;
          $stabMenuPdf['pdf'] = $filename;
          $stabMenuPdf['stablishment_id'] = $idStab;
          $fileRes = stablishmentMenuPdf::create($stabMenuPdf);
          $data['hahs'] = Crypt::encryptString($fileRes->idMenuPdf);
          $data['path'] = $fileRes->path;
          $data['pdf'] = $fileRes->pdf;
          $res['action'] = 'insert';
        }
        if($fileRes){
          if($file->storeAs($pathImageRel, $filename, 'public')){
            $res['success']=true;
            $res['code']='success';
            $res['message']= 'El archivo se cargó correctamente.';
            $res['data'] = ['file'=>$data];
          }else{
            $res['success']=false;
            $res['code']='error';
            $res['message']= 'El archivo no se cargó correctamente.';
          }
        }
      }

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Elimina un menu ya sea pdf o imagen.
   *
   * @return \Illuminate\Http\Response
   */
  public function delMenuObj(Request $req){
    $res=array('success'=>false, 'action'=>'load', 'data'=>[]);
    $fileRes;
    $path; $file; $resDel; $resReg;
    if($req->ajax()){
      $data = $req->input('data');

      $id = isset($data['hash']) && $data['hash'] ? Crypt::decryptString($data['hash']) : 0;
      $file = stablishmentMenuPdf::find($id);

      if($file){
        $path = storage_path('app/public/'.$file->path.$file->pdf);
        $resDel = File::delete($path);

        $file->deleted = 1;
        $resReg = $file->save();

        $res['success']=true;
        $res['code']='success';
        $res['data']=[$file, $path, $resDel, $resReg];
        $res['message']='Se ha eliminado el archivo del menú';
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']='No se pudo eliminar el archivo del menú.';
      }

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Carga los datos de una empresa
   *
   * @return \Illuminate\Http\Response
   */
  public function loadStab(Request $req){
    $res=array('success'=>false, 'action'=>'load');
    if($req->ajax()){
      $isStab =  session('isStablishment');
      $idStab = Crypt::decryptString(session('idStablishment'));

      // obteniendo datos del establecimiento del usuario
      $stab = Stablishment::
        select('s.*')
        ->from('stablishments AS s')
        ->where('s.deleted', 0)
        ->where('s.idstablishment', $idStab)
        ->first();

      $stab_tags = [];
      $stab_tags_ = StablishmentTag::where('stablishment_id', $idStab)
      ->where('deleted', 0)
      ->get();
      foreach ($stab_tags_ as $tag)
        $stab_tags[] = $tag->tag_id;

      $zones = Zone::where('deleted', 0)->get();
      $sections = Section::where('deleted', 0)->get();
      $tags = Tag::where('section_id', $stab->section_id)->where('deleted', 0)->get();

      $res['success']=true;
      $res['code']='success';
      $res['stab']= $stab;
      $res['stab_tags']= $stab_tags;
      $res['zones']= $zones;
      $res['sections']= $sections;
      $res['tags']= $tags;


      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Agregar un negocio
   *
   * @return \Illuminate\Http\Response
   */
  public function addStab(Request $req){
    $res=array('success'=>false, 'action'=>'add');
    if($req->ajax()){
      $data = $req->input('data');
      
      $nombre = isset($data[0]) && $data[0]['name']=='nombre' ? $data[0]['value'] : '';
      $descripcion = isset($data[1]) && $data[1]['name']=='descripcion' ? $data[1]['value'] : '';
      //$desc = nl2br(htmlentities($desc, ENT_QUOTES, 'UTF-8'));
      $descripcion = htmlentities(nl2br($descripcion), ENT_QUOTES, 'UTF-8');
      $descripcion = isset($data[2]) && $data[2]['name']=='descripcion' ? $data[2]['value'] : '';
      $direccion = isset($data[3]) && $data[3]['name']=='direccion' ? $data[3]['value'] : '';
      //$stab = Crypt::decryptString($stab);
      $latitud = isset($data[4]) && $data[4]['name']=='latitud' ? $data[4]['value'] : '';
      $longitud = isset($data[5]) && $data[5]['name']=='longitud' ? $data[5]['value'] : '';
      $telefono = isset($data[6]) && $data[6]['name']=='telefono' ? $data[6]['value'] : '';
      $whatsapp = isset($data[7]) && $data[7]['name']=='whatsapp' ? $data[7]['value'] : '';
      $facebook = isset($data[8]) && $data[8]['name']=='facebook' ? $data[8]['value'] : '';
      $instagram = isset($data[9]) && $data[9]['name']=='instagram' ? $data[9]['value'] : '';
      $twitter = isset($data[10]) && $data[10]['name']=='twitter' ? $data[10]['value'] : '';
      $youtube = isset($data[11]) && $data[11]['name']=='youtube' ? $data[11]['value'] : '';
      $web = isset($data[12]) && $data[12]['name']=='web' ? $data[12]['value'] : '';
      $horario = isset($data[13]) && $data[13]['name']=='horario' ? $data[13]['value'] : '';
      $zona = isset($data[14]) && $data[14]['name']=='zona' ? $data[14]['value'] : '';
      $section = isset($data[15]) && $data[15]['name']=='section' ? $data[15]['value'] : '';
      //html_entity_decode($desc, ENT_QUOTES, 'UTF-8');

      $job = StablishmentJob::updateOrCreate(
        ['idjob' => $idJob],
        [
          'name' => $name,
          'description' => $desc,
          'documentation' => $doc,
          'stablishment_id'=>$stab
        ]
      );

      if($job){
        $res['success']=true;
        $res['code']='success';
        $res['message']=' Se ha '.($idJob?'actualizado':'agregado').' una nueva vacante ('.$name.')';
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']='No se pudo '.$idJob?'editar':'crear'.' la vacante.';
      }
      $res['action']= $idJob ? 'upd' : 'add';

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Obtiene los tipos de empleos del catálogo
   *
   * @return \Illuminate\Http\Response
   */
  public function getJobTypes(Request $req){
    $res=array("success"=>false, "data"=>[]);
    if($req->ajax()){
      $data = $req->input("data");

      $jobs = StablishmentJob::select("jt.idJobType", "jt.name", "jt.description")
        ->from("cat_jobs_type AS jt")
        ->where("jt.deleted", 0)
        ->orderBy("jt.order", "asc")
        ->get();

      foreach ($jobs as $j) {
        $j->hashJobType = Crypt::encryptString($j->idJobType);
        $j->md5 = md5($j->idJobType);
        unset($j->idJobType);
      }

      if($jobs){
        $res["success"]=true;
        $res["code"]="success";
        $res["message"]="Listado de tipos de empleo.";
      }else{
        $res["success"]=false;
        $res["code"]="warning";
        $res["message"]="No se encontró ningún tipo de empleo";
      }
      $res['data']=$jobs;

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Obtiene los subtipos de empleos del catálogo
   *
   * @return \Illuminate\Http\Response
   */
  public function getJobSubTypes(Request $req){
    $res=array("success"=>false, "data"=>[]);
    if($req->ajax()){
      $data = $req->input("data");
      $idJobType = isset($data["hashJobType"]) && $data["hashJobType"] ? $data["hashJobType"] : "";
      $idJobType = $idJobType ? Crypt::decryptString($idJobType) : "";

      $subTypes = StablishmentJob::select("jst.idJobSubType", "jst.jobType_id", "jst.name", "jst.description")
        ->from("cat_jobs_subtype AS jst")
        ->where("jst.deleted", 0)
        ->where("jst.jobType_id", $idJobType)
        ->orderBy("jst.order", "asc")
        ->get();

      foreach ($subTypes as $sub) {
        $sub->hashJobSubType = Crypt::encryptString($sub->idJobSubType);
        $sub->md5 = md5($sub->idJobSubType);

        $sub->hashJobType = Crypt::encryptString($sub->jobType_id);
        //$sub->md5 = md5($sub->jobType_id);
        unset($sub->idJobSubType, $sub->jobType_id);
      }

      if($subTypes){
        $res["success"]=true;
        $res["code"]="success";
        $res["message"]="Listado de subtipos de empleo.";
      }else{
        $res["success"]=false;
        $res["code"]="warning";
        $res["message"]="No se encontró ningún subtipo de empleo";
      }
      $res['data']=$subTypes;

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Agregar una vacante a la empresa en cuestión
   *
   * @return \Illuminate\Http\Response
   */
  public function addJob(Request $req){
    $res=array('success'=>false, 'action'=>'add');
    if($req->ajax()){
      $data = $req->input('data');
      $name = isset($data["vacante"]) && $data["vacante"] ? $data["vacante"] : false;
      $desc = isset($data["descripcion"]) && $data["descripcion"] ? $data["descripcion"] : false;
      $desc = htmlentities(nl2br($desc), ENT_QUOTES, 'UTF-8');
      $idJobType = isset($data["jobType"]) && $data["jobType"] ? $data["jobType"] : false;
      $idJobType = Crypt::decryptString($idJobType);
      $doc = isset($data["doc"]) && $data["doc"] ? $data["doc"] : false;
      $stab = isset($data["stab"]) && $data["stab"] ? $data["stab"] : false;
      $stab = Crypt::decryptString($stab);
      $idJob = isset($data["job"]) && $data["job"] ? $data["job"] : false;
      $idJob = $idJob ? Crypt::decryptString($idJob) : false;
      //dd($idJob, $desc, $doc, $stab, $idJobType);

      $job = StablishmentJob::updateOrCreate(
        ["idjob" => $idJob],
        [
          "name" => $name,
          "description" => $desc,
          "documentation" => $doc,
          "stablishment_id"=>$stab,
          "jobType_id"=>$idJobType,
        ]
      );

      if(isset($job->idjob) && $job->idjob){
        $subTypes = isset($data['subTypes']) && is_array($data['subTypes']) ? $data['subTypes'] : [];

        // eliminar de forma lógica todas las subsecciones que tiene este registro
        if(is_array($subTypes) && count($subTypes)){
          StablishmentJobSubType::
            where('deleted', 0)
            ->where('job_id', $job->idjob)
            ->update(['deleted'=>1]);

          // agregar o actualizar las subsecciones/tags
          /*foreach ($tags as $tag) {
            StablishmentTag::updateOrCreate(
              ['stablishment_id'=>$idStab, 'tag_id'=>$tag],
              [ 'deleted'=>0 ]
            );
          }*/
        }

        if(is_array($subTypes)){
          foreach($subTypes as $sub){
            StablishmentJobSubType::create([
              'job_id'=>$job->idjob,
              'jobSubType_id'=> Crypt::decryptString($sub)
            ]);
          }
        }
      }

      if($job){
        $res['success']=true;
        $res['code']='success';
        $res['message']=' Se ha '.($idJob?'actualizado':'agregado').' una nueva vacante ('.$name.')';
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']='No se pudo '.$idJob?'editar':'crear'.' la vacante.';
      }
      $res['action']= $idJob ? 'upd' : 'add';

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Actualizar una vacante de la empresa en cuestión
   *
   * @return \Illuminate\Http\Response
   */
  public function updJob(Request $req){
    $res=array('success'=>false, 'action'=>'upd');
    if($req->ajax()){
      $data = $req->input('data');
      $idJob = isset($data['job']) && $data['job'] ? Crypt::decryptString($data['job']) : false;
      $name = isset($data['name']) && $data['name'] ? $data['name'] : false;
      $subTypes = isset($data['subTypes']) && is_array($data['subTypes']) ? $data['subTypes'] : [];

      $job = StablishmentJob::select(
        "j.name", "j.description", "j.documentation", "j.jobType_id", "jt.name AS jobTypeName")
        ->from("stablishments_jobs AS j")
        ->join('cat_jobs_type AS jt', 'jt.idJobType', '=', 'j.jobType_id')
        ->where('j.deleted', 0)
        ->where('j.idjob', $idJob)
        ->first();

      $job->description = strip_tags(html_entity_decode($job->description, ENT_QUOTES, 'UTF-8'));
      $job->hashJobType = Crypt::encryptString($job->jobType_id);

      $jobsSubtypes = StablishmentJobSubType::
        select("js.job_id", "js.jobSubType_id")
        ->from("stablishments_jobs_subtype AS js")
        ->where('js.deleted', 0)
        ->where('js.job_id', $idJob)
        ->get();
      foreach ($jobsSubtypes as $val) {
        $val->md5 = md5($val->jobSubType_id);
      }
      $job->subTypesSel = $jobsSubtypes;
        
      if($job){
        $res['success'] = true;
        $res['cont'] = $job;
        $res['message'] = '';
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']='No se pudo obtener la vacante.';
      }

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Eliminar una vacante de la empresa en cuestión
   *
   * @return \Illuminate\Http\Response
   */
  public function delJob(Request $req){
    $res=array('success'=>false, 'action'=>'del');
    $subTypes;
    if($req->ajax()){
      $data = $req->input('data');
      $job = isset($data['id']) && $data['id'] ? Crypt::decryptString($data['id']) : false;
      $name = isset($data['name']) && $data['name'] ? $data['name'] : false;
      $job = StablishmentJob::find($job);

      if($job){
        $job->deleted = 1;
        $job->save();

        StablishmentJobSubType::
          where('deleted', 0)
          ->where('job_id', $job->idjob)
          ->update(['deleted'=>1]);

        $res['success']=true;
        $res['code']='success';
        $res['message']=' Se ha eliminado la vacante ('.$name.')';
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']='No se pudo eliminar la vacante.';
      }

      $res = response()->json($res, 200);
    }
    return $res;
  }

  public function myJobs(Request $req){
    $res=array('success'=>false, 'action'=>'list');
    if($req->ajax()){
      $data = $req->input('data');
      $stab = isset($data['stab']) && $data['stab'] ? Crypt::decryptString($data['stab']) : false;

      $myJobs = self::myJobs_($stab);
      $myJobs = self::myJobsSetTable($myJobs);
      if($myJobs){
        $res['success']=true;
        $res['code']='success';
        $res['data']=$myJobs;
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']='No se pudo obtener las vacantes vacante.';
      }

      $res = response()->json($res, 200);
    }
    return $res;
  }

  private static function myJobs_($idStab){
    $myJobs = array();
    if($idStab){
      $myJobs = StablishmentJob::
        select('j.idjob', 'j.name', 'j.description', 'j.documentation', 'j.stablishment_id')
        ->from('stablishments_jobs AS j')
        ->join('stablishments AS stab', 'stab.idstablishment', '=', 'j.stablishment_id')
        ->where('j.deleted', 0)
        ->where('stab.deleted', 0)
        ->where('j.stablishment_id', $idStab)
        ->orderBy('j.created_at', 'desc')
        ->get();
    }
    return $myJobs;
  }

  private static function myJobsSetTable($myJobs){
    $myJobsTable = '';
    if(is_object($myJobs)){
      if(count($myJobs)){
        foreach ($myJobs as $job){
          $myJobsTable.='
            <tr id="'.md5($job['idjob']).'">
              <td>
                <div class="form-check">
                  <input class="form-check-input position-static" type="checkbox" value="'.$job->idjob.'" name="check">
                </div>
              </td>
              <td>'.$job->name.'</td>
              <td>'.html_entity_decode($job->description, ENT_QUOTES, 'UTF-8').'</td>
              <td>'.$job->documentation.'</td>
              <td><a href="" id="updJob" class="btn btn-outline-success" data-job="'.Crypt::encryptString($job['idjob']).'" data-name="'.$job->name.'">Editar</a></td>
              <td><a href="" id="delJob" class="btn btn-outline-danger" data-job="'.Crypt::encryptString($job['idjob']).'" data-name="'.$job->name.'">Eliminar</a></td>
            </tr>';
        }
      }else{
        $myJobsTable.='
          <tr id="tr-none">
            <td scope="row" colspan="10"><h4 class="text-center">SIN REGISTROS</h4></td>
          </tr>';
      }
    }
    return $myJobsTable;
  }

  /**
   * Agregar un anuncio a la empresa en cuestión
   *
   * @return \Illuminate\Http\Response
   */
  public function addAd(Request $req){
    $res=array('success'=>false, 'action'=>'add');
    if($req->ajax()){
      $data = $req->input('data');
      $name = isset($data[0]) && $data[0]['name']=='titleAd' ? $data[0]['value'] : '';
      $name = Crypt::decryptString($name);
      $desc = isset($data[1]) && $data[1]['name']=='descripcionAd' ? $data[1]['value'] : '';
      $desc = htmlentities(nl2br($desc), ENT_QUOTES, 'UTF-8');
      $stab = isset($data[2]) && $data[2]['name']=='stab' ? $data[2]['value'] : '';
      $stab = Crypt::decryptString($stab);
      $idAd = isset($data[3]) && $data[3]['name']=='ad' ? $data[3]['value'] : '';
      $idAd = $idAd ? Crypt::decryptString($idAd) : false;

      $ad = StablishmentAd::updateOrCreate(
        ['idad' => $idAd],
        [
          'name' => $name,
          'description' => $desc,
          'stablishment_id'=>$stab
        ]
      );

      if($ad){
        $res['success']=true;
        $res['code']='success';
        $res['message']=' Se ha '.($idAd?'actualizado':'agregado').' el  nuevo anuncio.';
        $res['ad']=Crypt::encryptString($ad['idad']);
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']='No se pudo '.$idAd?'editar':'crear'.' el anuncio.';
      }
      $res['action']= $idAd ? 'upd' : 'add';

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Actualizar un anuncio de la empresa en cuestión
   *
   * @return \Illuminate\Http\Response
   */
  public function updAd(Request $req){
    $res=array('success'=>false, 'action'=>'upd');
    if($req->ajax()){
      $data = $req->input('data');
      $ad = isset($data['ad']) && $data['ad'] ? Crypt::decryptString($data['ad']) : false;

      $ad = StablishmentAd::find($ad);
      $ad->description = strip_tags(html_entity_decode($ad->description, ENT_QUOTES, 'UTF-8'));
      if($ad){
        $res['success'] = true;
        $res['cont'] = $ad;
        $res['message'] = '';
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']='No se pudo obtener el anuncio.';
      }

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Eliminar un anuncio de la empresa en cuestión
   *
   * @return \Illuminate\Http\Response
   */
  public function delAd(Request $req){
    $res=array('success'=>false, 'action'=>'del');
    if($req->ajax()){
      $data = $req->input('data');
      $ad = isset($data['id']) && $data['id'] ? Crypt::decryptString($data['id']) : false;

      $ad = StablishmentAd::find($ad);

      if($ad){
        $ad->deleted = 1;
        $ad->save();
        $res['success']=true;
        $res['code']='success';
        $res['message']=' Se ha eliminado el anuncio';
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']='No se pudo eliminar el anuncio.';
      }

      $res = response()->json($res, 200);
    }
    return $res;
  }

  public function myDatas(Request $req){
    $res=array('success'=>false, 'action'=>'list');
    $myDatas;
    if($req->ajax()){
      $data = $req->input('data');
      $stab = isset($data['stab']) && $data['stab'] ? Crypt::decryptString($data['stab']) : false;
      $ty = isset($data['ty']) && $data['ty'] ? $data['ty'] : false;
      if($ty=='jobs'){
        $myDatas = self::myJobs_($stab);
        $myDatas = self::myJobsSetTable($myDatas);
      }else{
        $myDatas = self::myAds_($stab);
        $myDatas = self::myAdsSetTable($myDatas);
      }
      
      if($myDatas){
        $res['success']=true;
        $res['code']='success';
        $res['data']=$myDatas;
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']='No se pudo obtener los registros.';
      }

      $res = response()->json($res, 200);
    }
    return $res;
  }

  private static function myAds_($idStab){
    $myAds = array();
    if($idStab){
      $myAds = StablishmentAd::
        select('a.idad', 'a.name', 'a.description', 'a.stablishment_id')
        ->from('stablishments_ads AS a')
        ->join('stablishments AS stab', 'stab.idstablishment', '=', 'a.stablishment_id')
        ->where('a.deleted', 0)
        ->where('stab.deleted', 0)
        ->where('a.stablishment_id', $idStab)
        ->orderBy('a.created_at', 'desc')
        ->get();
        //->first();
    }
    return $myAds;
  }

  private static function myMenus_($idStab){
    $myMenus = array();
    if($idStab){
      $myMenus = StablishmentMenu::
        select('m.idMenu', 'm.name', 'm.description', 'm.stablishment_id')
        ->from('stablishments_menus AS m')
        ->join('menus_products AS mp', 'mp.menu_id', '=', 'm.idmenu')
        ->join('stablishments AS stab', 'stab.idstablishment', '=', 'm.stablishment_id')
        ->where('m.disabled', 0)
        ->where('mp.disabled', 0)
        //->where('stab.disabled', 0)
        ->where('m.deleted', 0)
        ->where('mp.deleted', 0)
        ->where('stab.deleted', 0)
        ->where('m.stablishment_id', $idStab)
        ->orderBy('m.created_at', 'desc')
        ->get();
    }
    return $myMenus;
  }

  /**
   * Habilita/deshabilita el chat de un establecimiento
   *
   * @param  Request $req
   * @return \Illuminate\Http\Response
   */
  public function enableChat(Request $req)
  {
    $res=array('success'=>false);
    if($req->ajax()){
      $enable='';
      $data = $req->input('data');
      $enableChat = isset($data['enableChat']) && boolval($data['enableChat']) ? $data['enableChat'] : 0;
      $stab = isset($data['stab']) && $data['stab'] ? Crypt::decryptString($data['stab']) : false;
      //DB::enableQueryLog();
      $enable = Stablishment::where('deleted', 0)
        ->where('idstablishment', $stab)
        ->update(['enablechat' => $enableChat]);
      //dd(DB::getQueryLog());
      if($enable){
        session(['chatStablishment' => $enableChat]);
        $res = response()->json(array('success'=>true, 'result'=>$enable), 200);
      }else{
        $res = response()->json(array('success'=>false, 'result'=>'No se pudo activar/desactivar el chat.'), 200);
      }
    }
    return $res;
  }

  private static function myAdsSetTable($myAds){
    $myAdsTable = '';
    if(is_object($myAds)){
      if(count($myAds)){
        foreach ($myAds as $ad){
          $myAdsTable.='
            <tr>
              <td>
                <div class="form-check">
                  <input class="form-check-input position-static" type="checkbox" value="'.$ad->idad.'" name="check">
                </div>
              </td>
              <td>'.$ad->name.'</td>
              <td>'.html_entity_decode($ad->description, ENT_QUOTES, 'UTF-8').'</td>
              <td><a href="" id="updAd" class="btn btn-outline-success" data-ad="'.Crypt::encryptString($ad['idad']).'" data-name="'.$ad->name.'">Editar</a></td>
              <td><a href="" id="delAd" class="btn btn-outline-danger" data-ad="'.Crypt::encryptString($ad['idad']).'" data-name="'.$ad->name.'">Eliminar</a></td>
            </tr>';
        }
      }else{
        $myAdsTable.='
          <tr id="tr-none">
            <td scope="row" colspan="10"><h4 class="text-center">SIN REGISTROS</h4></td>
          </tr>';
      }
    }
    return $myAdsTable;
  }

  public static function getBoundaries($lat, $lng, $distance=1, $earthRadius=6371){
    $return = array();
     
    // Los angulos para cada dirección
    $cardinalCoords = array(
      'north' => '0',
      'south' => '180',
      'east' => '90',
      'west' => '270'
    );

    $rLat = deg2rad($lat);
    $rLng = deg2rad($lng);
    $rAngDist = $distance/$earthRadius;

    foreach ($cardinalCoords as $name => $angle){
      $rAngle = deg2rad($angle);
      $rLatB = asin(sin($rLat) * cos($rAngDist) + cos($rLat) * sin($rAngDist) * cos($rAngle));
      $rLonB = $rLng + atan2(sin($rAngle) * sin($rAngDist) * cos($rLat), cos($rAngDist) - sin($rLat) * sin($rLatB));

      $return[$name] = array(
        'lat' => (float) rad2deg($rLatB), 
        'lng' => (float) rad2deg($rLonB)
      );
    }

    return array(
      'min_lat'  => $return['south']['lat'],
      'max_lat' => $return['north']['lat'],
      'min_lng' => $return['west']['lng'],
      'max_lng' => $return['east']['lng']
    );
  }

  /**
   * Agrega un establecimiento al perfil del usuario (mi espacio)
   *
   * @return \Illuminate\Http\Response
   */
  public function addStablishment(Request $req){
    $res=array('success'=>false, 'action'=>'add');
    if($req->ajax()){
      $data = $req->input('data');
      $idStab = isset($data['stab']) && $data['stab'] ? Crypt::decryptString($data['stab']) : false;
      $stabName = isset($data['stabName']) && $data['stabName'] ? Crypt::decryptString($data['stabName']) : false;

      $myStab = MyStablishment::select('idmystablishment')
        ->where('user_id', auth()->id())
        ->where('stablishment_id', $idStab)
        ->where('deleted', 0)
        ->get();

      if(!count($myStab)){
        MyStablishment::create([
          'user_id'=>auth()->id(),
          'stablishment_id'=>$idStab
        ]);

        $res['success']=true;
        $res['code']='success';
        $res['message']=$stabName.' ha sido agregado a tu lista personal.';
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']=$stabName.' ya existe en tu lista personal.';
      }

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Elimina un establecimiento del perfil del usuario (mi espacio)
   *
   * @return \Illuminate\Http\Response
   */
  public function delStablishment(Request $req){
    $res=array('success'=>false, 'action'=>'del');
    if($req->ajax()){
      $data = $req->input('data');
      $idMyStab = isset($data['stab']) && $data['stab'] ? Crypt::decryptString($data['stab']) : false;
      $stabName = isset($data['stabName']) && $data['stabName'] ? Crypt::decryptString($data['stabName']) : false;

      $myStab = MyStablishment::select('idmystablishment')
        ->where('idmystablishment', $idMyStab)
        ->where('deleted', 0)
        ->get();

      if(count($myStab)){
        $myStab = MyStablishment::find($idMyStab);
        $myStab->update(['deleted'=>1]);

        $res['success']=true;
        $res['code']='success';
        $res['message']=$stabName.' ha sido eliminado de tu lista personal.';
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']='Ocurrió un inconveniente, inténtalo más tarde.';
      }

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Obtiene los establecimientos dado uno o varios filtros
   *
   * @return \Illuminate\Http\Response
   */
  public function setFilter(Request $req)
  {
    $res=array('success'=>false);
    if($req->ajax()){
      $filters = $req->input('data');

      $stablish = Stablishment::select(
        'stab.idstablishment', 'stab.name', 'stab.description', 
        'stab.direction', 'stab.image', 'stab.offer')
        ->from('stablishments AS stab')
        ->join('municipios AS m', 'm.idmunicipio', '=', 'stab.municipio_id')
        ->join('estados AS e', 'e.idestado', '=', 'm.estado_id')
        ->join('sections AS s', 's.idsection', '=', 'stab.section_id')
        ->where('stab.disabled', 0)
        ->where('stab.deleted', 0)
        ->where('m.deleted', 0)
        ->where('e.deleted', 0)
        ->where('s.deleted', 0);
      
      if(isset($filters['tagFil']) && $filters['tagFil']){
        $stablish = $stablish->join('stablishments_tags AS st', 'st.stablishment_id', '=', 'stab.idstablishment');
        $stablish = $stablish->join('tags AS t', 't.idtag', '=', 'st.tag_id');
        $stablish = $stablish->where('st.tag_id', $filters['tagFil']);
      }

      if(isset($filters['section']) && $filters['section'])
        $stablish = $stablish->where('s.idsection', $filters['section']);

      if(isset($filters['nameFil']) && $filters['nameFil'])
        $stablish = $stablish->where('stab.name', 'like', ''.$filters["nameFil"].'%');

      if(isset($filters['zoneFil']) && $filters['zoneFil'])
        $stablish = $stablish->where('stab.zone_id', $filters['zoneFil']);

      $stablish = $stablish->orderByRaw('RAND()')->get();

      $res = response()->json(array('success'=>true, 'stablish'=>$stablish), 200);
    }
    return $res;
  }

  /**
   * Crea un nuevo registro de usuario
   *
   * @return \Illuminate\Http\Response
   */
  static public function createUser(array $data){
    $user = User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
      'stablishment' => $data['type'] == 'stablishment',
      'zone_id' => $data['zone'],
    ]);
    session(['userStablishment' => $user->stablishment]);
    return $user;
  }

  /**
   * Crea un nuevo registro de empresa
   *
   * @return \Illuminate\Http\Response
   */
  static public function createStablishment(array $data){
    $user = self::createUser($data);
    $facebook = $instagram = $youtube = $twitter = $web = '';
    $imageName = "img/site/stablishments/logos/default.png";
    $idStab; $pathImageAr; $pathImageAbs;
    $pathImageRel = $filename = '';
    /*if($request->hasFile("logotipo")){
      $imageName = str_replace(' ', '_', request('nombre')).'-logo.png';
      $image = $request->file("logotipo");
      $image->move($this->pathImage, $imageName);
    }*/

    /*if($data['facebook'])
      $facebook = str_replace('http://', 'https://', '', $data['facebook']);

    if($data['instagram'])
      $instagram = str_replace('http://', 'https://', '', $data['instagram']);

    if($data['youtube'])
      $youtube = str_replace(['http://', 'https://'], '', $data['youtube']);

    if($data['twitter'])
      $twitter = str_replace(['http://', 'https://'], '', $data['twitter']);

    if($data['web'])
      $web = str_replace(['http://', 'https://'], '', $data['web']);*/

    // guardando la imagen de la empresa si agregó una
    if($data['logotipoBase64']){
      $pathImageAr = makeDir();
      $pathImageAbs = isset($pathImageAr["absolute"]) ? $pathImageAr["absolute"] : "";
      $pathImageRel = isset($pathImageAr["relative"]) ? $pathImageAr["relative"] : "";
      $image_parts = explode(";base64,", $data['logotipoBase64']);
      $image_types_aux = explode("image/", $image_parts[0]);
      $image_type = $image_types_aux[1];
      $image_base64 = base64_decode($image_parts[1]);
      $filename = time().".".$image_type;
      $file = $pathImageAbs.$filename;
      if(file_put_contents($file, $image_base64)){

      }else{
        $message = "La imagen no se cargó correctamente.";
      }
    }

    $stab = Stablishment::create([
      'name'=>isset($data['nameStab']) ? $data['nameStab'] : '',
      'description'=>isset($data['descripcion']) ? $data['descripcion'] : '',
      'description2'=>isset($data['descripcion2']) ? $data['descripcion2'] : '',
      //'direction'=>isset($data['direccion']) ? $data['direccion'] : '',
      'lat'=>isset($data['latitud']) ? $data['latitud'] : '',
      'lng'=>isset($data['longitud']) ? $data['longitud'] : '',
      'image'=> $pathImageRel.$filename,
      //'summary'=>$summaryName,
      'phone'=>isset($data['telefono']) ? $data['telefono'] : '',
      'whatsapp'=>isset($data['whatsapp']) ? $data['whatsapp'] : '',
      /*'facebook'=>$facebook,
      'instagram'=>$instagram,
      'twitter'=>$twitter,
      'youtube'=>$youtube,
      'web'=>$web,
      'hour'=>isset($data['horario']) ? $data['horario'] : '',*/
      'offer'=>isset($data['oferta']) && $data['oferta'] ? 1 : 0,
      'disabled'=>0,
      //'expiration'=>'0000-00-00 00:00:00',
      'municipio_id'=>1,
      'zone_id'=>$data['zone'],
      'section_id'=>$data['section'],
      'user_id'=>$user->id,
      //'range'=>$data['visitas']
    ]);

    if(isset($stab->idstablishment) && $stab->idstablishment){
      $idStab = $stab->idstablishment;
      session(['idStablishment' => Crypt::encryptString($idStab)]);
      $tags = isset($data['tags']) && is_array($data['tags']) ? $data['tags'] : [];

      if(is_array($tags)){
        foreach ($tags as $tag) {
          StablishmentTag::create([
            'stablishment_id'=>$idStab,
            'tag_id'=>$tag
          ]);
        }
      }
    }
    return $user;
  }

  /**
   * Almacena/actualizar un establecimiento
   *
   * @return \Illuminate\Http\Response
   */
  public function updateStablishment(Request $req){
    $res=array("success"=>false);
    if($req->ajax()){
      $data = $req->input("data");
      $idStab; $stabRes; $pathImageAr; $pathImageAbs;
      $pathImageRel = $filename = "";
      $message = "";

      $name = $req->input("nombre");
      $description = $req->input("descripcion");
      $description2 = $req->input("descripcion2");
      //$direction = $req->input("direccion");
      $lat = $req->input("latitud");
      $lng = $req->input("longitud");
      $phone = $req->input("telefono");
      $whatsapp = $req->input("whatsapp");
      /*$facebook = cleanUrl($req->input("facebook"), "https://");
      $instagram = cleanUrl($req->input("instagram"), "https://");
      $twitter = cleanUrl($req->input("twitter"), "https://");
      $youtube = cleanUrl($req->input("youtube"), "https://");
      $web = cleanUrl($req->input("web"), "https://");
      $hour = $req->input("horario");*/
      $offer = intval($req->input("oferta"));
      $zone_id = $req->input("zona");
      $section_id = $req->input("section");
      $tags = $req->input("tags");
      //$enabled = intval($req->input("habilitado"));

      $validateRes = validate([
        'nombre' => [$name, 'required|max:155'],
        'descripcion' => [$description, 'required|max:200'],
        'descripcion2' => [$description2, 'required|max:100'],
        //'direccion' => [$direction, 'max:200'],
        'latitud' => [$lat, 'max:100'],
        'longitud' => [$lng, 'max:100'],
        'telefono' => [$phone, 'max:20'],
        'whatsapp' => [$whatsapp, 'max:10'],
        /*'facebook' => [$facebook, 'max:200'],
        'instagram' => [$instagram, 'max:200'],
        'twitter' => [$twitter, 'max:200'],
        'youtube' => [$youtube, 'max:200'],
        'web' => [$web, 'max:200'],
        'horario' => [$hour, 'max:200'],*/
        'zona' => [$zone_id, 'required'],
        'section' => [$section_id, 'required']
      ]);

      // si la validación es un éxito se procede a actualizar los datos de la empresa
      if(empty($validateRes)){
        if($req->input('logotipoBase64')){
          $pathImageAr = makeDir();
          $pathImageAbs = isset($pathImageAr["absolute"]) ? $pathImageAr["absolute"] : "";
          $pathImageRel = isset($pathImageAr["relative"]) ? $pathImageAr["relative"] : "";
          $image_parts = explode(";base64,", $req->input('logotipoBase64'));
          $image_types_aux = explode("image/", $image_parts[0]);
          $image_type = $image_types_aux[1];
          $image_base64 = base64_decode($image_parts[1]);
          $filename = time().".".$image_type;
          $file = $pathImageAbs.$filename;
          $saveImg = file_put_contents($file, $image_base64);
          if($saveImg){

          }else{
            $message = "La imagen no se cargó correctamente.";
          }
        }

        $idStab = Crypt::decryptString(session('idStablishment'));
        $stab = Stablishment::find($idStab);

        $stabData = [
          'name'=>$name,
          'description'=>$description,
          'description2'=>$description2,
          //'direction'=>$direction,
          'lat'=>$lat,
          'lng'=>$lng,
          //'summary'=>$summaryName,
          'phone'=>$phone,
          'whatsapp'=>$whatsapp,
          /*'facebook'=>$facebook,
          'instagram'=>$instagram,
          'twitter'=>$twitter,
          'youtube'=>$youtube,
          'web'=>$web,
          'hour'=>$hour,*/
          'offer'=>$offer?1:0,
          'zone_id'=>$zone_id,
          'section_id'=>$section_id,
          //'disabled'=>$enabled?0:1
        ];

        if($pathImageRel && $filename)
          $stabData['image'] = $pathImageRel.$filename;

        $stabRes = $stab->update($stabData);

        // si la actualización de datos es correcta, se procede a borrar los tags que se tengan y se agregan los nuevos
        if(is_array($tags) && count($tags)){
          // eliminar de forma lógica todas las subsecciones/tags que tiene este registro
          StablishmentTag::
            where('deleted', 0)
            ->where('stablishment_id', $idStab)
            ->update(['deleted'=>1]);

          // agregar o actualizar las subsecciones/tags
          foreach ($tags as $tag) {
            StablishmentTag::updateOrCreate(
              ['stablishment_id'=>$idStab, 'tag_id'=>$tag],
              [ 'deleted'=>0 ]
            );
          }
        }

        if($stabRes){
          $message = "Datos guardados de la empresa.".($message?" {$message}":"");
          $res = response()->json(array('success'=>true, 'message'=>$message, 'code'=>'success'), 200);
        }else{
          $message = "No se pudo actualizar los datos de la empresa, intenta más tarde.";
          $res = response()->json(array('success'=>false, 'message'=>$message, 'code'=>'error'), 200);
        }
      }else{
        $message = "Revisa los campos en rojo.";
        $res = response()->json(array('success'=>false, 'message'=>$message, 'code'=>'error', 'errors'=>$validateRes), 200);
      }
    }
    //$res = response()->json(array('success'=>true, 'message'=>'1111', 'code'=>'22222', 'errors'=>'333'), 200);
    return $res;
  }

  /**
   * Habilita/deshabilita un establecimiento
   *
   * @return \Illuminate\Http\Response
   */
  public function enableDisableStab(Request $req){
    $res=array("success"=>false);
    if($req->ajax()){
      $data = $req->input("data");
      $idStab; $stabRes;
      $message = "";
      $enabled = intval($data["habilitado"]);
      $idStab = Crypt::decryptString(session("idStablishment"));
      $stab = Stablishment::find($idStab);
      $stabRes = $stab->update(["disabled"=>$enabled?1:0]);
      if($stabRes){
        $message = "El establecimiento ha sido ".($enabled?"deshabilitado":"habilitado");
        $res = response()->json(array('success'=>true, 'message'=>$message, 'code'=>'success'), 200);
      }else{
        $message = "No se pudo habilitar/deshabilitar el establecimiento, intenta más tarde.";
        $res = response()->json(array('success'=>false, 'message'=>$message, 'code'=>'error'), 200);
      }
    }
    return $res;
  }

  /**
   * Se cargan las imagenes de la galería
   *
   * @return \Illuminate\Http\Response
   */
  public function loadGallery(Request $req){
    $res=array('success'=>false);
    if($req->ajax()){
      $idStab = Crypt::decryptString(session('idStablishment'));
      

      $imageObj = StablishmentGallery::select('idgallery', 'path', 'image')
        ->where('deleted', 0)
        ->where('stablishment_id', $idStab)
        ->get();

      foreach ($imageObj as $image) {
        $image->hashGallery = Crypt::encryptString($image->idgallery);
        unset($image->idgallery);
      }

      if(!empty($imageObj)){

        $message = "Imágenes de la galería.";
        $res = response()->json(array('success'=>true, 'message'=>$message, 'code'=>'success', 'data'=>$imageObj), 200);
      }else{
        $message = "No hay imágenes de la galería.";
        $res = response()->json(array('success'=>true, 'message'=>$message, 'code'=>'success'), 200);
      }
    }
    //$res = response()->json(array('success'=>true, 'message'=>'1111', 'code'=>'22222', 'errors'=>'333'), 200);
    return $res;
  }

  /**
   * Se agregan o actualizan las imágenes de la galería del negocio
   *
   * @return \Illuminate\Http\Response
   */
  public function storeGallery(Request $req){
    $res=array('success'=>false);
    if($req->ajax()){
      $data = $req->input('data');
      $idStab; $galRes; $imageHash; $pathImageAr; $pathImageAbs; $local; $file; $saveImg;
      $pathImageRel = $filename = '';
      $galleryData = $errors = [];
      $message = '';


      if($req->input('imageBase64')){
        foreach($req->input('imageBase64') as $imageBase64){
          $imageBase64 = explode('|HASH|', $imageBase64);
          $imageHash = $imageBase64[1];
          $imageHash = strpos($imageHash, 'hash') === false ? Crypt::decryptString($imageHash) : false;

          $image_parts = explode(";base64,", $imageBase64[0]);
          $image_types_aux = explode("image/", $image_parts[0]);
          $image_type = $image_types_aux[1];
          $image_base64 = base64_decode($image_parts[1]);

          // si el hash es un id de la tabla gallery se otiene dicho registro para hacer un update, si no existe se hace una inserción
          if($imageHash){
            $imageObj = StablishmentGallery::select('idgallery', 'path', 'image')
              ->where('deleted', 0)
              ->where('idgallery', $imageHash)
              ->first();

            $local = env('APP_ENV') == "local";
            $pathImageAbs = $local ? public_path() : base_path();
            $pathImageAbs = $pathImageAbs."/public_html/".$imageObj->path;
            $filename = $imageObj->image;
            $file = $pathImageAbs.$filename;
            File::delete($file);
            //$imageObj->update(['deleted'=>1]);
            //dd($image_type, $pathImageAbs, $imageObj->image, $imageObj->path, $file);
            $saveImg = file_put_contents($file, $image_base64);
          }else{
            $pathImageAr = makeDir("gallery");
            $pathImageAbs = isset($pathImageAr["absolute"]) ? $pathImageAr["absolute"] : "";
            $pathImageRel = isset($pathImageAr["relative"]) ? $pathImageAr["relative"] : "";
            $filename = time().".".$image_type;
            $file = $pathImageAbs.$filename;
            //dd($image_type, $pathImageAbs, $filename, $pathImageAbs, $file);
            $saveImg = file_put_contents($file, $image_base64);
            if($saveImg){
              $idStab = Crypt::decryptString(session('idStablishment'));

              $galleryData['path'] = $pathImageRel;
              $galleryData['image'] = $filename;
              $galleryData['stablishment_id'] = $idStab;
              StablishmentGallery::create($galleryData);
            }else{
              $errors[] = "La imagen no se cargó correctamente.";
            }
          }
          /*$pathImageAr = makeDir();
          $pathImageAbs = isset($pathImageAr["absolute"]) ? $pathImageAr["absolute"] : "";
          $pathImageRel = isset($pathImageAr["relative"]) ? $pathImageAr["relative"] : "";*/
          /*$image_parts = explode(";base64,", $imageBase64[0]);
          $image_types_aux = explode("image/", $image_parts[0]);
          $image_type = $image_types_aux[1];
          $image_base64 = base64_decode($image_parts[1]);*/
          /*$filename = time().".".$image_type;
          $file = $pathImageAbs.$filename;
          $saveImg = file_put_contents($file, $image_base64);*/
          /*if($saveImg){
            $idStab = Crypt::decryptString(session('idStablishment'));

            $galleryData['path'] = $pathImageRel;
            $galleryData['image'] = $filename;
            $galleryData['stablishment_id'] = $idStab;
            StablishmentGallery::create($galleryData);
          }else{
            $errors[] = "La imagen no se cargó correctamente.";
          }*/
        }

        $message = "Imágenes guardadas correctamente.";
        $res = response()->json(array('success'=>true, 'message'=>$message, 'code'=>'success'), 200);
      }else{
        $message = "Algunas imágenes no pudieron ser guardadas.";
        $res = response()->json(array('success'=>false, 'message'=>$message, 'code'=>'error', 'errors'=>$errors), 200);
      }
    }
    //$res = response()->json(array('success'=>true, 'message'=>'1111', 'code'=>'22222', 'errors'=>'333'), 200);
    return $res;
  }

  /**
   * Se agregan o actualizan las redes sociales del negocio
   *
   * @return \Illuminate\Http\Response
   */
  public function storeSocial(Request $req){
    $res=array("success"=>false);
    if($req->ajax()){
      $data = $req->input("data");
      $facebook = isset($data["facebook"]) && $data["facebook"] ? $data["facebook"] : null;
      $instagram = isset($data["instagram"]) && $data["instagram"] ? $data["instagram"] : null;
      $twitter = isset($data["twitter"]) && $data["twitter"] ? $data["twitter"] : null;
      $youtube = isset($data["youtube"]) && $data["youtube"] ? $data["youtube"] : null;
      $web = isset($data["web"]) && $data["web"] ? $data["web"] : null;
      $idStab = isset($data["hashStab"]) && $data["hashStab"] ? $data["hashStab"] : null;
      $idStab = $idStab ? Crypt::decryptString($idStab) : null;

      $idStab; $socialData; $facebook; $instagram; $twitter; $youtube; $web;
      $message = '';

      $socialData = [ ];
      $facebook && $socialData["facebook"] = cleanUrl($facebook, "https://");
      $instagram && $socialData["instagram"] = cleanUrl($instagram, "https://");
      $twitter && $socialData["twitter"] = cleanUrl($twitter, "https://");
      $youtube && $socialData["youtube"] = cleanUrl($youtube, "https://");
      $web && $socialData["web"] = $web;

      $socialRes = Stablishment::
        where('deleted', 0)
        ->where('idstablishment', $idStab)
        ->update($socialData);

      if($socialRes){
        $message = "Se actualizaron las redes sociales.";
        $res = response()->json(array('success'=>true, 'message'=>$message, 'code'=>'success'), 200);
      }else{
        $message = "No se pudo actualizar las redes sociales.";
        $res = response()->json(array('success'=>false, 'message'=>$message, 'code'=>'error'), 200);
      }
    }
    return $res;
  }

  /**
   * Se cargan las redes sociales
   *
   * @return \Illuminate\Http\Response
   */
  public function loadSocial(Request $req){
    $res=array('success'=>false);
    if($req->ajax()){
      $data = $req->input("data");
      $idStab = isset($data["hashStab"]) && $data["hashStab"] ? $data["hashStab"] : null;
      $idStab = $idStab ? Crypt::decryptString($idStab) : null;      

      $stab = Stablishment::
        select("s.facebook", "s.instagram", "s.twitter", "s.youtube", "s.web")
        ->from('stablishments AS s')
        ->where('s.deleted', 0)
        ->where('s.idstablishment', $idStab)
        ->first();

      if(!empty($stab)){
        $message = "Redes sociales.";
        $res = response()->json(array('success'=>true, 'message'=>$message, 'code'=>'success', 'data'=>$stab), 200);
      }else{
        $message = "No se encontraron las redes sociales.";
        $res = response()->json(array('success'=>true, 'message'=>$message, 'code'=>'success'), 200);
      }
    }
    //$res = response()->json(array('success'=>true, 'message'=>'1111', 'code'=>'22222', 'errors'=>'333'), 200);
    return $res;
  }

  /**
   * Detalle del negocio elegido
   *
   * @return \Illuminate\Http\Response
   */
  public function test1(){
    $test='';
    return view('site.test1', compact('test'));
  }

  public function test2(Request $req){
    // http://www.michael-pratt.com/blog/7/Encontrar-Lugares-cercanos-con-MySQL-y-PHP/
    $res=array('success'=>false);
    if($req->ajax()){
      $data = $req->input('data');
      $lat = isset($data['lat']) && $data['lat'] ? $data['lat'] : 0.0;
      $lng = isset($data['lng']) && $data['lng'] ? $data['lng'] : 0.0;
      $distance = 1; // Sitios que se encuentren en un radio de 1KM
      $box = self::getBoundaries($lat, $lng, $distance);

/*
      // 6,371 KM (radio medio de la tierra en KM)
      $query = '
        SELECT idstablishment, name, lat, lng, (
          6371 * ACOS(
            COS(RADIANS('.$lat.'))
            * COS(RADIANS(lat))
            * COS(RADIANS(lng)
            - RADIANS('.$lng.'))
            + SIN( RADIANS('.$lat.'))
            * SIN(RADIANS( lat ))
          )
        ) AS distance 
        FROM stablishments 
        WHERE (lat BETWEEN ? AND ?)
        AND (lng BETWEEN ? AND ?)
        HAVING distance < ?
        ORDER BY distance ASC';

      $stablish = DB::select($query, array($box['min_lat'], $box['max_lat'], $box['min_lng'], $box['max_lng'], $distance));
*/

      $stablish = Stablishment::select(
      's.idstablishment', 's.name', 's.description', 's.description2', 
      's.direction', 's.image', 's.offer', 's.lat', 's.lng',
      'sc.image AS secImage')
      ->from('stablishments AS s')
      ->join('sections AS sc', 'sc.idsection', '=', 's.section_id')
      ->where('s.disabled', 0)
      ->where('s.deleted', 0)
      ->get();

      $res = response()->json(array('success'=>true, 'lat'=>$lat, 'lng'=>$lng, 'stablish'=>$stablish, 'req'=>$req), 200);
    }
    return $res;
  }

  public function loadRegisterTags(Request $req){
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

/* ******************************************* */
/* ***** MÓDULO DE CURRICULUM DE USUARIO ***** */
/* ******************************************* */

  /**
   * Carga los datos de un cv
   *
   * @return \Illuminate\Http\Response
   */
  public function loadCv(Request $req){
    $res=array('success'=>false, 'action'=>'load');
    if($req->ajax()){
      $idUser = auth()->id();

      // obteniendo datos del CV del usuario
      $cv = UserJobProfile::
        select('c.*')
        ->from('user_jobprofile AS c')
        ->where('c.deleted', 0)
        ->where('c.user_id', $idUser)
        ->first();
      /*$stab_tags = [];
      $stab_tags_ = StablishmentTag::where('stablishment_id', $idStab)
      ->where('deleted', 0)
      ->get();
      foreach ($stab_tags_ as $tag)
        $stab_tags[] = $tag->tag_id;*/

      //$zones = Zone::where('deleted', 0)->get();
      //$sections = Section::where('deleted', 0)->get();
      //$tags = Tag::where('section_id', $stab->section_id)->where('deleted', 0)->get();

      $res['success']=true;
      $res['code']='success';
      $res['cv']= $cv;
      //$res['stab_tags']= $stab_tags;
      //$res['zones']= $zones;
      //$res['sections']= $sections;
      //$res['tags']= $tags;


      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Agregar una vacante a la empresa en cuestión
   *
   * @return \Illuminate\Http\Response
   */
  public function addCv(Request $req){
    $res=array('success'=>false, 'action'=>'add');
    if($req->ajax()){

      /*
      $desc = isset($data["descripcion"]) && $data["descripcion"] ? $data["descripcion"] : false;
      $desc = htmlentities(nl2br($desc), ENT_QUOTES, 'UTF-8');
      $idJobType = Crypt::decryptString($idJobType);
      $doc = isset($data["doc"]) && $data["doc"] ? $data["doc"] : false;
      $stab = isset($data["stab"]) && $data["stab"] ? $data["stab"] : false;
      $stab = Crypt::decryptString($stab);
      $idJob = isset($data["job"]) && $data["job"] ? $data["job"] : false;
      $idJob = $idJob ? Crypt::decryptString($idJob) : false;*/
      $hashCv = $req->input("hashCv");
      $hashCv = $hashCv ? Crypt::decryptString($hashCv) : false;
      $name = $req->input("name");
      $nextName = $req->input("nextName");
      $ap = $req->input("ap");
      $am = $req->input("am");
      $email = $req->input("email");
      $cellphone = $req->input("cellphone");
      $age = $req->input("age");
      $gender = $req->input("gender");
      $description = $req->input("description");
      $cadademicH = $req->input("cadademicH");
      $jobH = $req->input("jobH");
      $disabled = $req->input("disabled");
      $jobType = $req->input("jobType");
      $jobType = $jobType ? Crypt::decryptString($jobType) : false;
      $subTypes = $req->input("subTypes");

      dd($name, $nextName, $disabled, $jobType, $subTypes);

      $cv = StablishmentJob::updateOrCreate(
        ["idjob" => $idJob],
        [
          "name" => $name,
          "description" => $desc,
          "documentation" => $doc,
          "stablishment_id"=>$stab,
          "jobType_id"=>$idJobType,
        ]
      );


      if(isset($job->idjob) && $job->idjob){
        $subTypes = isset($data['subTypes']) && is_array($data['subTypes']) ? $data['subTypes'] : [];

        /*
        // eliminar de forma lógica todas las subsecciones que tiene este registro
        if(is_array($subTypes) && count($subTypes)){
          StablishmentJobSubType::
            where('deleted', 0)
            ->where('job_id', $$job->idjob)
            ->update(['deleted'=>1]);

          // agregar o actualizar las subsecciones/tags
          foreach ($tags as $tag) {
            StablishmentTag::updateOrCreate(
              ['stablishment_id'=>$idStab, 'tag_id'=>$tag],
              [ 'deleted'=>0 ]
            );
          }
        }
        */
        if(is_array($subTypes)){
          foreach ($subTypes as $sub) {
            StablishmentJobSubType::create([
              'job_id'=>$job->idjob,
              'jobSubType_id'=> Crypt::decryptString($sub)
            ]);
          }
        }
      }

      if($job){
        $res['success']=true;
        $res['code']='success';
        $res['message']=' Se ha '.($idJob?'actualizado':'agregado').' una nueva vacante ('.$name.')';
      }else{
        $res['success']=false;
        $res['code']='warning';
        $res['message']='No se pudo '.$idJob?'editar':'crear'.' la vacante.';
      }
      $res['action']= $idJob ? 'upd' : 'add';

      $res = response()->json($res, 200);
    }
    return $res;
  }

  /**
   * Almacena/actualizar un CV
   *
   * @return \Illuminate\Http\Response
   */
  public function updateCv(Request $req)
  {
    $res=array("success"=>false);
    if($req->ajax()){
      $data = $req->input("data");
      $idCv; $cvRes; $pathImageAr; $pathImageAbs;
      $pathImageRel = $filename = "";
      $message = "";

      $idCv = $req->input("idCv") ? Crypt::decryptString($req->input("idCv")) : 0;
      $name = $req->input("name");
      $nextName = $req->input("nextName");
      $ap = $req->input("ap");
      $am = $req->input("am");
      $email = $req->input("email");
      $cellphone = $req->input("cellphone");
      $age = $req->input("age");
      $gender = $req->input("gender");
      $description = $req->input("description");
      $cadademicH = $req->input("cadademicH");
      $jobH = $req->input("jobH");
      $disabled = $req->input("disabled");
      
      //$tags = $req->input("tags");

      $validateRes = validate([
        'name' => [$name, 'required|min:2|max:50'],
        'nextName' => [$nextName, 'max:50'],
        'ap' => [$ap, 'required|min:2|max:50'],
        'am' => [$am, 'max:50'],
        'email' => [$email, 'required|email|max:90'],
        'cellphone' => [$cellphone, 'min:10|max:13'],
        'age' => [$age, 'required|numeric|min:18|max:99'],
        'gender' => [$gender, 'numeric|min:0|max:1'],
        'description' => [$description, 'max:250'],
        'cadademicH' => [$cadademicH, 'max:250'],
        'jobH' => [$jobH, 'max:250'],
      ]);

      // si la validación es un éxito se procede a actualizar los datos del cv
      if(empty($validateRes)){
        /*if($req->input('logotipoBase64')){
          $pathImageAr = makeDir();
          $pathImageAbs = isset($pathImageAr["absolute"]) ? $pathImageAr["absolute"] : "";
          $pathImageRel = isset($pathImageAr["relative"]) ? $pathImageAr["relative"] : "";
          $image_parts = explode(";base64,", $req->input('logotipoBase64'));
          $image_types_aux = explode("image/", $image_parts[0]);
          $image_type = $image_types_aux[1];
          $image_base64 = base64_decode($image_parts[1]);
          $filename = time().".".$image_type;
          $file = $pathImageAbs.$filename;
          $saveImg = file_put_contents($file, $image_base64);
          if($saveImg){

          }else{
            $message = "La imagen no se cargó correctamente.";
          }
        }*/

        //$idStab = Crypt::decryptString(session('idStablishment'));
        $cv = UserJobProfile::find($idCv);

        $cvData = [
          'name'=>$name,
          'nextName'=>$nextName,
          'ap'=>$ap,
          'am'=>$am,
          'email'=>$email,
          'cellphone'=>$cellphone,
          //'summary'=>$summaryName,
          'age'=>$age,
          'gender'=>$gender,
          'description'=>$description,
          'cadademicH'=>$cadademicH,
          'jobH'=>$jobH,
          'disabled'=>$disabled,
        ];

        //if($pathImageRel && $filename)
          //$cvData['image'] = $pathImageRel.$filename;

        $cvRes = $cv->update($cvData);

        // si la actualización de datos es correcta, se procede a borrar los tags que se tengan y se agregan los nuevos
        //if($cvRes && is_array($tags) && count($tags)){
        if($cvRes){
          // eliminar de forma lógica todas las subsecciones/tags que tiene este registro
          /*StablishmentTag::
            where('deleted', 0)
            ->where('stablishment_id', $idStab)
            ->update(['deleted'=>1]);

          // agregar o actualizar las subsecciones/tags
          foreach ($tags as $tag) {
            StablishmentTag::updateOrCreate(
              ['stablishment_id'=>$idStab, 'tag_id'=>$tag],
              [ 'deleted'=>0 ]
            );
          }*/

          $message = "Datos guardados cel CV.".($message?" {$message}":"");
          $res = response()->json(array('success'=>true, 'message'=>$message, 'code'=>'success'), 200);          
        }else{
          $message = "No se pudo actualizar los datos del cv, intenta más tarde.";
          $res = response()->json(array('success'=>false, 'message'=>$message, 'code'=>'error'), 200);
        }
      }else{
        $message = "Revisa los campos en rojo.";
        $res = response()->json(array('success'=>false, 'message'=>$message, 'code'=>'error', 'errors'=>$validateRes), 200);
      }
    }
    //$res = response()->json(array('success'=>true, 'message'=>'1111', 'code'=>'22222', 'errors'=>'333'), 200);
    return $res;
  }

  /**
   * Página  principal
   *
   * @return \Illuminate\Http\Response
   */
  public function games(){
    return view('site.games');
  }
}
