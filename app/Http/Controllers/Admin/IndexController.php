<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Node;

class IndexController extends Controller
{

    // login成功画面の画面表示
    public function index(){
        // sessionからユーザー権限keyを取得
        $allow_node = array_keys(session('admin.node'));
    
        // ユーザー権限により、表示のメニューが変更していく
        $menuData = Node::where('is_menu','1') -> whereIn('id',$allow_node) -> get() -> toArray();
    	return view('admin.index.index',compact('menuData'));
    }

    // login成功welcome画面
    public function welcome(){
    	return view('admin.index.welcome');
    }

    // logout画面
    public function logout(){
    	auth() ->guard('admin')-> logout();
		return redirect(route('front.logout')) -> with('success','ログアウトしました');
    }
}
