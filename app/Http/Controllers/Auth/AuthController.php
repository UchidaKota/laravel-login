<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * @return view
     */
    public function showLogin(){
        return view('login.login_form');
    }

    /**
     * @param App\Http\Requests\LoginFormRequest
     */
    public function login(LoginFormRequest $request){
        $credentials = $request->only('email', 'password');

        $user = $this->user->getUserEmail($credentials['email']);

        if(!is_null($user)){
            if($this->user->isAccountLocked($user)){
                return back()->withErrors([
                    'danger' => 'アカウントがロックされています。',
                ]);
            }

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                $this->user->resetErrorCount($user);
    
                return redirect()->route('home')->with('success', 'ログインが成功しました。');
            }

            $user->error_count = $this->user->addErrorCount($user->error_count);

            if($this->user->lockAccount($user)){
                return back()->withErrors([
                    'danger' => 'アカウントがロックされました。',
                ]);
            }
            
            $user->save();
        }

        return back()->withErrors([
            'danger' => 'メールアドレスかパスワードが間違っています。',
        ]);
    }

    /**
     * ユーザーをアプリケーションからログアウトさせる
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('showLogin')->with('success', 'ログアウトしました。');;
    }
}
