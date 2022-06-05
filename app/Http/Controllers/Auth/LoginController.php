<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use Exception;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
{
  // return $request;
   $credentials = $request->only('email','password');
   $remember = $request->filled('remember');
   if(!Auth::validate($credentials)):
      return redirect()->to('login')
                ->withErrors(trans('auth.failed'));
   endif;

   if (Auth::attempt($credentials, $remember)) {
     $request->session()->regenerate();
     return redirect('/home');
   }
   // $user = Auth::getProvider()->retrieveByCredentials($credentials);

   // Auth::login($user, $request->get('remember'));
   return redirect('/login');
   // return $this->authenticated($request, $user);
}

    /**
     * Redirect the user to the Google authentication page.
    *
    * @return \Illuminate\Http\Response
    */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
      return $request;

        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
        // only allow people with @c-230.com to login
        if(explode("@", $user->email)[1] !== 'c-230.com'){
            return redirect()->to('/');
        }
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            // log them in
            if(!$existingUser->avatar){
                $existingUser->avatar = $user->avatar;
            }
            $existingUser->google_id = $user->id;
            $existingUser->avatar_original = $user->avatar_original;
            if (!$existingUser->email_verified_at) {
                $existingUser->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
            }
            $existingUser->save();
            auth()->login($existingUser, true);
        } else {
            // create a new user
            $newUser = new User;
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->role_id = 4;
            $newUser->active = 1;
            $newUser->google_id = $user->id;
            $newUser->avatar = $user->avatar;
            $newUser->avatar_original = $user->avatar_original;
            $newUser->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
            $newUser->save();
            auth()->login($newUser, true);
        }
        return redirect()->to('/home');
    }
}
