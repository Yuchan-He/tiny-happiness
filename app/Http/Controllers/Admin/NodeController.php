<?php

namespace App\Http\Controllers\Admin;

use App\Models\Node;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NodeController extends Controller
{
    /**
     * リス一覧画面.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // すべての権限を取得して、表示する
        $data = Node::all();
        return view('admin.node.index',compact('data'));

    }

    /**
     * 新規データ入力画面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.node.create');
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
            'name' => 'required | unique:nodes,name',
            'route_name' => 'required',
            'is_menu' => 'required',
        ]);

        $post = $request -> except(['_token']);
        $Model = Node::create($post);
        return $Model ? '追加しました' :'追加失敗しました';
        
    }

    /**
     * 修正画面を表示
     *
     * @param  \App\Models\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function edit(Node $node)
    {
        $model = $node;
        return view('admin.node.edit',compact('model'));
    }

    /**
     * 修正データを更新して、保存する
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Node $node)
    {

        $data = $request -> except(['_token']);
        $node -> update($data);
        return redirect(route('admin.node.index')) -> with('success','情報を更新しました');
    }

    /**
     * 削除機能
     *
     * @param  \App\Models\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function destroy(Node $node)
    {
        Node::destroy($node -> id); 
        return ['status' => 0,'msg' => '削除しました'];
    }
}
