<?php

namespace App\Http\Controllers\Auth;

use App\Models\Tag;
use App\Models\User;
use App\Models\Zone;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Crypt;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;





class RegisterController extends Controller{
  /*
  |--------------------------------------------------------------------------
  | Register Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles the registration of new users as well as their
  | validation and creation. By default this controller uses a trait to
  | provide this functionality without requiring any additional code.
  |
  */

  use RegistersUsers;

  /**
   * Where to redirect users after registration.
   *
   * @var string
   */
  protected $redirectTo = RouteServiceProvider::HOME;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(){
    $this->middleware('guest');
  }

  public function showRegistrationForm(){
    $sections = Section::where('deleted', 0)->get();
    $zones = Zone::where('deleted', 0)->get();
    return view('auth.register', compact('zones', 'sections'));
  }

  /**
   * Get a validator for an incoming registration request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data){
    //dd('validator', $data);
    $validator = [
      'name' => ['required', 'min:5', 'max:155'],
      'email' => ['required', 'string', 'email', 'min:9', 'max:155', 'unique:users'],
      'password' => ['required', 'string', 'min:8', 'max:155', 'confirmed'],
      'zone' => ['required', 'numeric'],
    ];

    if($data['type'] == 'stablishment'){
      $validator['nameStab'] = ['required', 'max:155'];
      $validator['descripcion'] = ['required', 'string', 'max:200'];
      $validator['descripcion2'] = ['required', 'string', 'max:100'];
      $validator['direccion'] = ['string', 'max:200'];
      $validator['latitud'] = ['max:20'];
      $validator['longitud'] = ['max:20'];
      $validator['descripcion'] = ['required', 'string', 'max:200'];
      //$validator['logotipo'] = ['image|mimes:jpeg,png|max:200|dimensions:min_width=90,min_height=55,max_width=110,max_height=75'];
      $validator['telefono'] = ['max:20'];
      $validator['whatsapp'] = ['max:20'];
      $validator['facebook'] = ['max:200'];
      $validator['instagram'] = ['max:200'];
      $validator['twitter'] = ['max:200'];
      $validator['youtube'] = ['max:200'];
      $validator['horario'] = ['max:200'];
      $validator['section'] = ['required'];
      //$validator['tags'] = ['required'];
    }
    return Validator::make($data, $validator);
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array  $data
   * @return \App\Models\User
  */
  protected function create(array $data){
    //self::$redirectTo = $data['type'] != 'stablishment' ? self::$redirectTo : '/myspace';
    return $data['type'] != 'stablishment' ? 
      SiteController::createUser($data) : SiteController::createStablishment($data);
  }

  public function redirectTo(){
    $userStablishment = session('userStablishment');
    session()->forget('userStablishment');
    if($userStablishment){
      return '/myspace';
    }else{
      return '/';
    }
  }
}
