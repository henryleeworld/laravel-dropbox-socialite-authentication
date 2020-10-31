<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\User;
use Socialite;
  
class DropboxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToDropbox()
    {
        return Socialite::driver('dropbox')->setScopes(['account_info.read'])->redirect();
    }
      
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleDropboxCallback()
    {
        try {
            $user = Socialite::driver('dropbox')->user();
            $finduser = User::where('dropbox_id', $user->id)->first();
            if($finduser) {
                Auth::login($finduser);
                return redirect('/dashboard');
            }else{
                $newUser = User::create([
                    'name'           => $user->name,
                    'email'          => $user->email,
                    'dropbox_id'     => $user->id,
                    'dropbox_avatar' => $user->avatar,
                    'password'       => encrypt('123456dummy')
                ]);
                Auth::login($newUser);
     
                return redirect('/dashboard');
            }
    
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

