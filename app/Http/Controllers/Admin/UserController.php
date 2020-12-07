<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Role;
use Hash;

class UserController extends BaseController
{
    /**
    * リス一覧画面 + 検索機能
    * @param id
    * @return リスト画面
    */
    public function index(Request $request){
        // 検索のデータを取得
        $kw = $request -> get('kw');
        // データ合計数を取得
        $sum = User::count();

        // 検索の内容はUserにあるかどうか判断する
        $data = User::when($kw, function($query) use($kw) {
            $query -> where('username','like',"%{$kw}%");
        }) -> orderBy('created_at','desc') -> paginate($this -> pagesize);

    	return view('admin.user.index',compact('data','kw','sum'));
    }

	/**
    * 新規データ入力画面
    * @param null 
    * @return view
    */
    public function create(){
    	return view('admin.user.create');
    }

	/**
    * 新規データ提出の検証・保存
    * @param Reqquest $request
    * @return data
    */
    public function store(Request $request){
    	// ユーザーが提出したデータを検証する
        $this ->validate($request,
            ['username' => 'required | unique:users,username',
             'password' => 'required | confirmed',
             'mobile' => 'required | mobile',
             'email' => 'required',
             'sex' => 'required'           
            ]);

        // 保存しないデータを除外する
        $post = $request -> except(['_token','password_confirmation']);
        $post['password'] = bcrypt($request -> password);
        // ユーザーの権限を一般ユーザーとして付与する
        $post['role_id'] ='4';        
        $userModel = User::create($post);
        return $userModel ? '新しいユーザーを追加しました' :'ユーザーを追加失敗しました';    
    }

   
    /**
    * 論理削除機能
    * @param Request $request
    * @return null
    */
    public function del(int $id){
        User::find($id) -> delete();
        return ['status' => 0,'msg' => '削除しました'];
    }

    /**
    * 削除したユーザー画面
    * @param 
    * @return null
    */
    public function indexdeleted(){

        // 削除したユーザーを抽出
        $deletedUsers = User::onlyTrashed() -> get();
        return view('admin.user.deleted',compact('deletedUsers'));
    }

    /**
    * ユーザーを復元機能
    * @param Request $request
    * @return null
    */
    public function restore(int $id){
        // ユーザーを復元する
        User::onlyTrashed() -> where('id',$id) ->restore();
        return redirect(route('admin.user.indexdeleted')) -> with('success','ユーザーを復元しました。ユーザーリストに確認ください');
    }

    /**
    * ユーザーを永久削除機能
    * @param Request $request
    * @return null
    */
    public function deleted(int $id){
        // ユーザーをDBから削除
        User::onlyTrashed() -> where('id',$id) ->forceDelete();
        return ['status' => 0,'msg' => 'ユーザーを永久に削除しました'];
    }


    /**
    * 修正画面を表示
    * @param id
    * @return view
    */
    public function edit(int $id){
        $model = User::find($id);
        return view('admin.user.edit',compact('model'));
    }


    /**
    * ユーザー編集提出画面
    * @param id
    * @return view　id
    */
    public function update(Request $request,int $id){
       
        $model = User::find($id);
        $password = $model -> password;
        // パスワードを検証する
        $spass = $request -> get('spassword');
        $bool = Hash::check($spass,$password);
        if($bool){
            $data = $request -> except(['_token','password_confirmation','spassword']);
            if(!empty($data['password'])){
                $data['password'] = bcrypt($request -> password);
            }else{
                unset($data['password']);
            }
            $model -> update($data);
            return redirect(route('admin.user.index')) -> with('success','情報を更新しました'); 
        }else{
            return redirect(route('admin.user.edit',$model)) -> withErrors(['error_pw' => 'パスワードが一致しておりません']);
        }
        
    }

    /**
    * 個人情報編集表示画面
    * @param id
    * @return view
    */
    public function editPersonal(int $id){
        // 選択したユーザーを表示する
        $model = User::find($id);
        return view('admin.user.editPersonal',compact('model'));
    }

    /**
    * 個人情報編集提出画面
    * @param id
    * @return view　id
    */
    public function updatePersonal(Request $request,int $id){
        
        $model = User::find($id);
        $password = $model -> password;
        // パスワードを検証する
        $spass = $request -> get('spassword');
        $bool = Hash::check($spass,$password);
        if($bool){
            $data = $request -> except(['_token','password_confirmation','spassword']);
            if(!empty($data['password'])){
                $data['password'] = bcrypt($request -> password);
            }else{
                unset($data['password']);
            }
            $model -> update($data);
            return '情報を更新しました'; 
        }else{
            return redirect(route('admin.user.editPersonal',$model)) -> withErrors(['error_pw' => 'パスワードが一致しておりません']);
        }
        
    }

    /**
    * ユーザー権限変更画面　+　変更データを更新して、保存する
    * @param id
    * @return view
    */
    public function updateRole(Request $request,int $id){
        // ルート上、match(get,post)の方法が設定されていたので、リクエストの方法を先に判断する

        // postかどうか判断する
        if($request -> isMethod('post')){
            // データを検証する
            $post = $this -> validate($request,[
                'role_id' => 'required'
            ],['role_id.required' => '役割をご選択ください']);
            User::find($id) -> update($post);
            return redirect(route('admin.user.index')) -> with('success','情報を更新しました');


        }
        // GETの場合、編集画面表示する
        $roleAll = Role::all();
        $role_id = User::where('id',$id) -> pluck('role_id') -> toArray();
        return view('admin.user.role',compact('id','role_id','roleAll'));
    }        
}
