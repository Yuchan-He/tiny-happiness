<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Node;

class RoleController extends BaseController
{
    /**
     * リス一覧画面
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // データを取得
        $data = Role::all();
        return view('admin.role.index',compact('data'));

    }

    /**
     * 新規データ入力画面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * 新規データ提出の検証・保存
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ユーザーが提出したデータを検証する
        $this -> validate($request,[
            'roleName' => 'required | unique:roles,roleName'
        ]);

        $post = $request -> only(['roleName']);
        $roleModel = Role::create($post);
        return $roleModel ? '追加しました' :'追加失敗しました';

    }

    /**
     * 修正画面を表示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $model = Role::find($id);      
        return view('admin.role.edit',compact('model'));
    }

    /**
     * 修正データを更新して、保存する
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $model = Role::find($id);
        $data = $request -> except(['_token']);
        $model -> update($data);
        return redirect(route('admin.role.index')) -> with('success','情報を更新しました');  
    }

    /**
     * 削除機能
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        Role::destroy($id); 
        return ['status' => 0,'msg' => '削除しました'];

    }

    /**
     * 権限の按分機能
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function node(Role $role)
    {
        // すげての権限を取得
        $nodeAll = Node::all() ->toArray();
        // ユーザー権限を取得
        $nodes = $role -> nodes() -> pluck('node_id') -> toArray();
        return view('admin.role.node',compact('role','nodeAll','nodes'));
    }

    /**
     * 権限の編集・更新
     *
     * @param  Request $request,Role $role 
     * @return 
     */
    public function nodeSave(Request $request,Role $role)
    {
        $role -> nodes() -> sync($request ->get('node'));
        return redirect(route('admin.role.index')) -> with('success','情報を更新しました');
    }


}
