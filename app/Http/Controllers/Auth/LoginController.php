<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\Stablishment;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
      $myStab = Stablishment::select('stab.idstablishment', 
      'stab.name', 'stab.description', 'stab.image',
      'stab.user_id', 'stab.range', 'stab.enablechat')
      ->from('stablishments AS stab')
      ->where('stab.deleted', 0)
      ->where('stab.user_id', auth()->id())
      ->first();

      if(is_object($myStab)){
        session(['isStablishment' => true]);
        session(['idStablishment' => Crypt::encryptString($myStab->idstablishment)]);
        session(['nameStablishment' => $myStab->name]);
        session(['userStablishment' => Crypt::encryptString($myStab->user_id)]);
        session(['logoStablishment' => $myStab->image]);
        session(['chatStablishment' => $myStab->enablechat]);
      }else{
        session(['isStablishment' => false]);
      }

      if (auth()->user()->profile_id==1) {
          return '/admin/stablishments';
      }

      return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /*
    Fuente: https://tutsforweb.com/redirect-login-register-custom-method/#:~:text=After%20every%20successful%20login%2Fregistration,available%20in%20the%20trait%20RedirectsUsers%20.
    
    public function redirectTo(){
      if (auth()->user()->is_admin){
        return '/admin/dashboard';
      }else if(auth()->user()->is_authenticated){
        return '/app';
      }else{
        return '/home';
      }
    }
    */
}
