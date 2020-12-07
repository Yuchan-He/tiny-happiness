<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;

class SignupController extends Controller
{	
	/** 
	* @param null 
	* 新規登録（signup)画面
	*/
	public function index(){
		return view('front.login.signup');
	}

	/** 
    * ユーザー名の唯一性を検証する
	* @param null 
	* 新規登録（signupUsername)画面
	*/
	public function signupUsername(Request $request){
        // ユーザが提出したデータを検証する        
        $username = $this ->validate($request,
            ['username' => 'required | unique:users,username']); 

        if(!$username){
            // ユーザー名を使えない場合
            $data = 0;
            return $data;
        }else{
            // ユーザー名を使える場合
            $data = 1;
            return $data;            
        }
	}
    /** 
    * 
    * @param null 
    * 新規登録（signup)画面
    */
    public function signup(Request $request){
        // ユーザが提出したデータを検証する
        $this ->validate($request,
            ['username' => 'required | unique:users,username',
             'password' => 'required'       
            ]);

        // 保存しないデータを除外する
        $post = $request -> except(['_token']);
        // パスワードをハッシュ化
        $post['password'] = bcrypt($request -> password);
        // 自動的に一般なユーザー権限を付与する
        $post['role_id'] ='4';
        // 新規ユーザーをDBへ登録
        $userModel = User::create($post);
        if(!$userModel){
            return redirect(route('front.login.signup')); 
        }
        // 登録成功した後に、一旦ログアウトの状況に戻って、ログインの画面に遷移する
        auth() ->guard('admin')-> logout();
        return redirect(route('front.login')) -> with('success','登録成功しました、ログインしてくだい');
    }    
}
