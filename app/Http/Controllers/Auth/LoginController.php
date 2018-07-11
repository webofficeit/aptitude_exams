<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

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
    protected $redirectTo = '/';

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
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('email', $request->get('email'))->first();
        if($user and $user->suspended)
            return redirect()->back()->withErrors(['email'=>'Your account is no longer active! Contact administrator at aptitudetvm@gmail.com!']);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


//    public function authenticated(Request $request,User $user){
//        $previous_session = $user->session_id;
//
//        $role = $user->roles->first();
////        dd($previous_session, $user->updated_at->diffInHours(Carbon::now()) <=2);
//        if( (!$role || $role->name != 'admin') and $previous_session and $user->updated_at->diffInHours(Carbon::now()) <=2){
//            $this->guard()->logout();
//            $request->session()->flush();
//            $request->session()->regenerate();
//            return redirect('login')->withErrors(['email'=> 'You are logged in from another computer! Please try login after some time.' ]);
//        }
//
////        dd(\Session::getId(), $previous_session);
//        if ($previous_session) {
//            \Session::getHandler()->destroy($previous_session);
//        }
//
//        Auth::user()->session_id = \Session::getId();
//        Auth::user()->save();
//        return redirect()->intended($this->redirectPath());
//    }

//    public function logout(Request $request)
//    {
//        Auth::user()->session_id = null;
//        Auth::user()->save();
//
//        $this->guard()->logout();
//
//        $request->session()->flush();
//
//        $request->session()->regenerate();
//
////        dd($request);
//        return redirect('/login');
//    }
}
