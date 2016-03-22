<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Userlanguage;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Mail, Auth, DB;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    protected $redirectPath = '/edit';

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
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
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|
                           regex:"~^(?=[^a-z]*[a-z])(?=[^A-Z]*[A-Z])(?=[^0-9]*[0-9])(?=[^!$#%]*[!$#‌‌​​%]).*$~"|
                           min:8|
                           confirmed',
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
        $userdetails = User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'alias' => preg_replace('#[^\w.]#','',iconv('utf-8','ASCII//IGNORE//TRANSLIT',$data['firstname'].".".$data['lastname']."_".mt_rand(100,999))),
            /*'alias' => $data['firstname'].".".$data['lastname']."_".mt_rand(100,999),*/
        ]);
        /* Set default language German ranking status 4 for all registerd users */
        Userlanguage::insert(['user_id' => $userdetails->id, 'language_id' => 8, 'ranking' => 4]);
        /* commented for beta*/
        /*Mail::send('emails.publishprofile', ['emailid' => $data['email'], 'userID' => $userdetails->id ],function ($message) use ($userdetails) {
            $message->to($userdetails->email)->subject('Confirm your profile is available for public');
        });
        */
        return $userdetails;
    }
}
