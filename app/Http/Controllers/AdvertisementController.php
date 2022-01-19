<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class AdvertisementController extends Controller
{
  private $pathImage = "img/site/advertisements";

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
    $ads = Advertisement::select('a.idadvertisements', 
      'a.image', 'a.name', 'a.description', 'a.data', 'a.notes')
      ->from('advertisements AS a')
      ->where('a.deleted', 0)
      ->orderBy('created_at', 'desc')
      ->get();

    return view('site.admin.advertisements', compact('ads'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(){
    return view('site.admin.advertisementCreate');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request){
    $ext;
    $image;
    $imageName='default.png';

    request()->validate([
      'nombre'=>'required|min:1|max:100',
      'descripcion'=>'max:255',
      'datos'=>'max:255',
      'notas'=>'max:255',
      //'image' => "image|mimes:jpeg,png,svg|max:250|dimensions:min_width=480,min_height=680,max_width=520,max_height=720"
      'image' => "image|mimes:jpeg,png,svg|max:250"
    ]);

    if($request->hasFile("image")){
      $ext = $request->file("image")->extension();
      $imageName = str_replace(' ', '_', request('nombre')).'-image.'.$ext;
      $image = $request->file("image");
      $image->move($this->pathImage, $imageName);
    }

    $stab = Advertisement::create([
      'name'=>request('nombre'),
      'description'=>request('descripcion'),
      'data'=>request('datos'),
      'notes'=>request('notas'),
      'image'=>$imageName
    ]);

    return redirect()->route('admin.advertisements');
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
  public function edit(Advertisement $adv){
    
    return view('site.admin.advertisementEdit', compact('adv'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Advertisement $adv){
    $ext;
    $image;
    $imageName='default.png';

    // comprobar si el establecimiento se llama igual o cambio de nombre. Si cambio, hay que eliminar la imagen
    $adv_ = Advertisement::find(request('adv'));
    $imageName = $adv_->image;
    $imageNameUrl = '';

    if(is_object($adv_)){
      if(request("image") && $adv_->image!='default.png'){
        $imageNameUrl = public_path().'/img/site/advertisements/'.$adv_->image;
        is_file($imageNameUrl) && unlink($imageNameUrl);
      }
    }

    request()->validate([
      'nombre'=>'required|min:1|max:100',
      'descripcion'=>'max:255',
      'datos'=>'max:255',
      'notas'=>'max:255',
      'image' => "image|mimes:jpeg,png,svg|max:250"
    ]);

    if(request('image')){
      $ext = request('image')->extension();
      $imageName = str_replace(' ', '_', request('nombre')).'-image.'.$ext;
      $image = request('image');
      $image->move($this->pathImage, $imageName);
    }

    $adv->update([
      'name'=>request('nombre'),
      'description'=>request('descripcion'),
      'data'=>request('datos'),
      'notes'=>request('notas'),
      'image'=>$imageName
    ]);

    return redirect()->route('admin.advertisements');
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
}
