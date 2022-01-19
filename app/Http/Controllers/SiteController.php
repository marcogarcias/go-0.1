<?php

namespace App\Http\Controllers;

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
use App\Models\StablishmentAd;
use App\Models\StablishmentJob;
use App\Models\StablishmentTag;
use App\Models\StablishmentMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

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
   * Lista los negocios de la sección elegida
   *
   * @return \Illuminate\Http\Response
   */
  public function stablishments($sec=null){
    if($sec){
      $stablish = Stablishment::select(
        'stab.idstablishment', 'stab.name', 'stab.description', 
        'stab.direction', 'stab.image', 'stab.offer')
        ->from('stablishments AS stab')
        ->join('municipios AS m', 'm.idmunicipio', '=', 'stab.municipio_id')
        ->join('estados AS e', 'e.idestado', '=', 'm.estado_id')
        ->join('sections AS s', 's.idsection', '=', 'stab.section_id')
        //->join('stablishments_tags AS st', 'st.stablishment_id', '=', 'stablishments.idstablishment')
        //->join('tags AS t', 't.idtag', '=', 'st.tag_id')
        ->where('s.idsection', $sec)
        ->where('stab.disabled', 0)
        ->where('stab.deleted', 0)
        ->where('m.deleted', 0)
        ->where('e.deleted', 0)
        ->where('s.deleted', 0)
        ->orderByRaw('RAND()')
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
    $stablish = Stablishment::select(
      's.idstablishment', 's.name', 's.description', 's.direction', 
      's.image', 's.summary', 's.whatsapp', 's.facebook', 's.range', 's.enablechat', 
      's.instagram', 's.twitter', 's.youtube', 's.user_id', 'sec.idsection', 'sec.image AS secImage')
      ->from('stablishments AS s')
      //'t.name AS tagName', 't.description AS tagDesc', 't.image AS tagImage')
      ->join('municipios AS m', 'm.idmunicipio', '=', 's.municipio_id')
      ->join('estados AS e', 'e.idestado', '=', 'm.estado_id')
      ->join('sections AS sec', 'sec.idsection', '=', 's.section_id')
      //->join('stablishments_tags AS st', 'st.stablishment_id', '=', 's.idstablishment')
      //->join('tags AS t', 't.idtag', '=', 'st.tag_id')
      ->where('s.idstablishment', $stab)
      ->where('s.disabled', 0)
      ->where('s.deleted', 0)
      ->where('m.deleted', 0)
      ->where('e.deleted', 0)
      ->where('sec.deleted', 0)
      ->first();
      
    if(!is_object($stablish))
      return redirect()->route('home');
    $stablish->range += 1;
    $stablish->save();
    $jobs = self::myJobs_($stablish['idstablishment']);
    $ads = self::myAds_($stablish['idstablishment']);
    return view('site.stablishment', compact('idUser', 'stablish', 'jobs', 'ads'));
  }

  /**
   * Establecimientos que el usuario ha agregado
   *
   * @return \Illuminate\Http\Response
   */
  public function mySpace(){
    $this->middleware('auth');
    $mySpace;
    $myStab = $myJobs = $myAds = $sections = $zones = $tags = $menus = [];
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

    if($iAmStab){
      $sections = Section::where('deleted', 0)->get();
      $zones = Zone::where('deleted', 0)->get();
      $tags = Tag::where('deleted', 0)->get();

      $myStab = Stablishment::select('stab.idstablishment', 
        'stab.name', 'stab.description', 'stab.image',
        'stab.user_id', 'stab.range')
        ->from('stablishments AS stab')
        ->where('stab.deleted', 0)
        ->where('stab.user_id', auth()->id())
        ->first(auth()->id());
      if(is_object($myStab)){
        $menus = self::myMenus_($myStab['idstablishment']);
        $myJobs = self::myJobs_($myStab['idstablishment']);
        $myAds = self::myAds_($myStab['idstablishment']);
      }
    }
    return view('site.mySpace', compact('mySpace', 'myStab', 'myJobs', 'myAds', 'chat', 'iAmStab', 'sections', 'zones', 'tags', 'menus'));
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
      }
      $res['success']=true;
      $res['code']='success';
      $res['menus']= $menus;

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
      $idStab; $idMenu; $menu; $idProduct; $action; $validate;
      $prods = [];
      $idStab = Crypt::decryptString(session('idStablishment'));
      $data = $req->input('data');

      $idMenu = isset($data[0]) && $data[0]['name']=='hashMenu' ? $data[0]['value'] : '';
      $idMenu = strpos($idMenu, 'hash') === false ? Crypt::decryptString($idMenu) : null;

      $menuName = isset($data[1]) && $data[1]['name']=='menuName' ? $data[1]['value'] : '';
      $menuDescripcion = isset($data[2]) && $data[2]['name']=='menuDescripcion' ? $data[2]['value'] : '';
      $menuDisable = isset($data[3]) && $data[3]['name']=='menuDisable' ? true : false;

      if($menuName){
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
              'description' => $prod['description'],
              'menu_id' => $menu->idmenu
            ]
          );
        }
      }else{
        //$validate = ['menuName'=>];
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
        $res['success']=false;
        $res['code']='warning';
        $res['message']='No se pudo {$action} el menú.';
        $res['validate']=$validate;
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
      $horario = isset($data[12]) && $data[12]['name']=='horario' ? $data[12]['value'] : '';
      $zona = isset($data[13]) && $data[13]['name']=='zona' ? $data[13]['value'] : '';
      $section = isset($data[14]) && $data[14]['name']=='section' ? $data[14]['value'] : '';
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
   * Agregar una vacante a la empresa en cuestión
   *
   * @return \Illuminate\Http\Response
   */
  public function addJob(Request $req){
    $res=array('success'=>false, 'action'=>'add');
    if($req->ajax()){
      $data = $req->input('data');
      $name = isset($data[0]) && $data[0]['name']=='vacante' ? $data[0]['value'] : '';
      $desc = isset($data[1]) && $data[1]['name']=='descripcion' ? $data[1]['value'] : '';
      //$desc = nl2br(htmlentities($desc, ENT_QUOTES, 'UTF-8'));
      $desc = htmlentities(nl2br($desc), ENT_QUOTES, 'UTF-8');
      $doc = isset($data[2]) && $data[2]['name']=='doc' ? $data[2]['value'] : '';
      $stab = isset($data[3]) && $data[3]['name']=='stab' ? $data[3]['value'] : '';
      $stab = Crypt::decryptString($stab);
      $idJob = isset($data[4]) && $data[4]['name']=='job' ? $data[4]['value'] : '';
      $idJob = $idJob ? Crypt::decryptString($idJob) : false;
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
   * Actualizar una vacante de la empresa en cuestión
   *
   * @return \Illuminate\Http\Response
   */
  public function updJob(Request $req){
    $res=array('success'=>false, 'action'=>'upd');
    if($req->ajax()){
      $data = $req->input('data');
      $job = isset($data['job']) && $data['job'] ? Crypt::decryptString($data['job']) : false;
      $name = isset($data['name']) && $data['name'] ? $data['name'] : false;

      $job = StablishmentJob::find($job);

      //echo $job->description.'<br/>';
      $job->description = strip_tags(html_entity_decode($job->description, ENT_QUOTES, 'UTF-8'));
      //echo $job->description.'<br/>';
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
    if($req->ajax()){
      $data = $req->input('data');
      $job = isset($data['id']) && $data['id'] ? Crypt::decryptString($data['id']) : false;
      $name = isset($data['name']) && $data['name'] ? $data['name'] : false;
      $job = StablishmentJob::find($job);

      if($job){
        $job->deleted = 1;
        $job->save();
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
      $desc = isset($data[0]) && $data[0]['name']=='descripcionAd' ? $data[0]['value'] : '';
      $desc = htmlentities(nl2br($desc), ENT_QUOTES, 'UTF-8');
      $stab = isset($data[1]) && $data[1]['name']=='stab' ? $data[1]['value'] : '';
      $stab = Crypt::decryptString($stab);
      $idAd = isset($data[2]) && $data[2]['name']=='ad' ? $data[2]['value'] : '';
      $idAd = $idAd ? Crypt::decryptString($idAd) : false;

      $ad = StablishmentAd::updateOrCreate(
        ['idad' => $idAd],
        [
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
  /*public function updAd(Request $req){
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
  }*/

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
        //->get();
        ->first();
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
        ->where('stab.disabled', 0)
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

  /*private static function myAdsSetTable($myAds){
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
  }*/

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
    $facebook = $instagram = $youtube = $twitter = '';
    $imageName = 'default.png';
    /*if($request->hasFile("logotipo")){
      $imageName = str_replace(' ', '_', request('nombre')).'-logo.png';
      $image = $request->file("logotipo");
      $image->move($this->pathImage, $imageName);
    }*/

    if($data['facebook'])
      $facebook = str_replace(array('http://', 'https://'), '', $data['facebook']);

    if($data['instagram'])
      $instagram = str_replace(array('http://', 'https://'), '', $data['instagram']);

    if($data['youtube'])
      $youtube = str_replace(array('http://', 'https://'), '', $data['youtube']);

    if($data['twitter'])
      $twitter = str_replace(array('http://', 'https://'), '', $data['twitter']);

    $stab = Stablishment::create([
      'name'=>isset($data['nameStab']) ? $data['nameStab'] : '',
      'description'=>isset($data['descripcion']) ? $data['descripcion'] : '',
      'description2'=>isset($data['descripcion2']) ? $data['descripcion2'] : '',
      'direction'=>isset($data['direccion']) ? $data['direccion'] : '',
      'lat'=>isset($data['latitud']) ? $data['latitud'] : '',
      'lng'=>isset($data['longitud']) ? $data['longitud'] : '',
      'image'=>$imageName,
      //'summary'=>$summaryName,
      'phone'=>isset($data['telefono']) ? $data['telefono'] : '',
      'whatsapp'=>isset($data['whatsapp']) ? $data['whatsapp'] : '',
      'facebook'=>$facebook,
      'instagram'=>$instagram,
      'twitter'=>$twitter,
      'youtube'=>$youtube,
      'hour'=>isset($data['horario']) ? $data['horario'] : '',
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
      $tags = $data['tags'];

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
}
