<?php

namespace App\Http\Controllers\AuthMember;

use Platform\Models\Members\Member;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

use \Platform\Controllers\Core;

use App\Notifications\MemberCreated;

class RegisterController extends Controller
{
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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = 'member/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('member.guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        //$this->validator($request->all())->validate();

        $validator = Validator::make($request->all(), [
            'sl' => 'required',
            'name' => 'required|max:64',
            'email' => 'required|email|max:255|unique:members'/*,
            'password' => 'required|min:6|confirmed',*/
        ]);

        if ($validator->fails()) {
          $errors = $validator->errors();

          return response()->json([
            'type' => 'error',
            'reset' => false, 
            'msg' => $errors->first()
          ]);
        } 

        event(new Registered($user = $this->create($request->all())));

        //$this->guard()->login($user);

        return response()->json([
          'type' => 'success',
          'fn' => 'memberRegistered'
        ]);

        //return redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'sl' => 'required',
            'name' => 'required|max:64',
            'email' => 'required|email|max:255|unique:members'/*,
            'password' => 'required|min:6|confirmed',*/
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        // Parse SL + send pincode
        $qs = Core\Secure::string2array($data['sl']);
        $password = mt_rand(1000, 9999);

        $reseller = Core\Reseller::get();

        $member = \Platform\Models\Members\Member::create([
            'reseller_id' => $reseller->id,
            'user_id' => $qs['user_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($password),
            'confirmation_code' => str_random(8),
        ]);

        // Send mail with credentials
        $member->notify(new MemberCreated($password, $reseller->url));

        return $member;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
      $sl = request()->input('sl', '');

      if($sl != '') {  
        return view('platform.members.auth.register', [
          'sl' => $sl
        ]);
      }
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return \Auth::guard('member');
    }
}
