<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
    * @param null 
    * @return view login画面
    */
    public function index(){
        //　ユーザーの登録状態を確認、すでにログインのユーザーは直接ログイン画面に行く
        if (auth() ->guard('admin')-> check()){
            return redirect(route('front.article.index'));
        }else{
    	   return view('front.login.login');
        }
        
    }

	/**
	* 検証ログインのデータ
    * @param Request $request 
    * @return login 成功画面
    */
    public function login(Request $request){
        //  提出のデータをチェックする
    	$post = $this ->validate($request,
    		['username' => 'required',
    		 'password' => 'required'
    		
    	]);
        //  提出のデータをDBでチェックする
        $bool = auth() -> guard('admin') -> attempt($post);
        if(!$bool){
            return redirect(route('admin.login')) -> withErrors(['error' => 'ユーザー名か、パスワードが間違っています']);
        }else{
            $data = auth() -> guard('admin') -> user();
            // ユーザー役割を取得する
            $roleModel = $data -> role; 
            // リレーションを通して、役割からユーザーの権限を取得
            $node =  $roleModel -> nodes() -> pluck('name','node_id') -> toArray();
            // 権限をsessionの中に保存していく
            session(['admin.node' => $node]);
            return redirect(route('admin.index'));
        }                
    }
}
