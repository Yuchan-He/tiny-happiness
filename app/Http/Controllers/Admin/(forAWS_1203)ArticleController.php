<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Article;
use Storage;

class ArticleController extends BaseController
{
    /**
     * リス一覧画面 + 検索機能
     * @param id
     * @return リスト画面
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // ユーザー役割とIDを取得
        $role = auth() -> guard('admin') -> user() -> role_id;
        $user_id = auth() -> guard('admin') -> user() -> id;
        $user_article = Article::where('user_id','=',$user_id) -> pluck('title');

        // ユーザー権限の判定、普通のユーザーなら自分の情報しか見れない
        if($role == 4){
            // 検索の機能のキーワードを取得
            $kw = $request -> get('kw');
            // 検索の内容はUserにあるかどうか判断して、ユーザーの文章を取得
            $user_article = Article::where('user_id','=',$user_id);
            // 文章の数を取得
            $sum = $user_article ->count();
            // 文章のデータを取得
            $data = $user_article -> when($kw, function($query) use($kw) {
                $query -> where('title','like',"%{$kw}%");
            }) -> orderBy('updated_at','desc') -> paginate($this -> pagesize);
            return view('admin.article.index',compact('data','kw','sum'));

        }else{
            // 検索の機能のキーワードを取得
            $kw = $request -> get('kw');
            // 文章の数を取得
            $sum = Article::count();

            // 検索の内容はUserにあるかどうか判断して、すべての文章を取得
            $data = Article::when($kw, function($query) use($kw) {
                $query -> where('title','like',"%{$kw}%");
            }) -> orderBy('updated_at','desc') -> paginate($this -> pagesize);
            return view('admin.article.index',compact('data','kw','sum'));
        }
    }

    /**
     * 新規データ入力画面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.article.create');        
    }

    /**
     * アップロードファイルの保存
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function webuploader(Request $request)
    {
        $file = $request -> file('file');
        if($request -> hasFile('file') && $file -> isValid()){
            // file名 名付けする
        $filename = time(). $file -> getClientOriginalName() . '.' . $file -> getClientOriginalExtension();

        // fileが一時保存場所を保存する
        $movefile = $file -> path();
        // fileの内容を取得する
        $fileContents = file_get_contents($movefile);
        // fileをS3へ保存
        $path = Storage::disk('s3') -> put($filename,$fileContents);
        // 保存したfileのurlを取得する,urlの中には、fileの保存場所を記入、
    　　//　S3に直接保存するとき、すなわちルートディレクトリに保存するとき、filenameだけでいい
        $url = Storage::disk('s3') -> url($filename);

    　　//　fileをS3のフォルダーに入れたとき、
    　　// $url = Storage::disk('s3') -> url("/XXX/".$filename);
    　　
    　　/*
         * 要らないコードを無効化
        dump($url);
        */

            $result = [
                'success' => 'アップロード成功しました',
                'path' => $url
            ];           
        }else{
            $result = [
                'error' => 'アップロード失敗',
                'errorMsg' => $file -> getErrorMessage()
            ];
        }
        // jsonの形でresponseする
        return response() -> json($result);

    }    

    /**
     * 新規データ提出の検証・保存
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //　データを検証する
        $this ->validate($request,
            ['title' => 'required',
             'body' => 'required'         
            ]);
        // 保存しないデータを除外する
        $post = $request -> except(['_token']);

        // アップロードのファイルがない時、ディフォルトの画像を使う
        $defaultPic = config('defaultPic.pic');
        if(empty($request -> pic)){
            $post['pic'] = $defaultPic;
        }else{

             $post['pic'] =Storage::disk('s3') -> url($pic);
        }


        //$post['pic'] = Storage::disk('s3') -> url($pic);
        //　データをDBへ保存
        $userModel = Article::create($post);
        return redirect(route('admin.article.create')) -> withErrors(['error' => '入力箇所を完了してください']);

    }


    /**
     * 修正画面を表示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {       
        $model = Article::find($id);
        return view('admin.article.edit',compact('model'));        
    }

    /**
     * 修正データを更新して、保存する
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // データ検証
        $bool = $this ->validate($request,
            ['title' => 'required',
             'body' => 'required'         
            ]);
        
        $model = Article::find($id);
        // 保存しないデータを除外する
        $post = $request -> except(['_token']);
        // アップロードのファイルがない時、ディフォルトの画像を使う
        $defaultPic = config('defaultPic.pic');
        if(empty($request -> pic)){
            $post['pic'] = $defaultPic;
        }
        // 有効なデータを保存する
        if($bool){
            $model -> update($post);
            return redirect(route('admin.article.index')) -> with('success','更新しました'); 
        }else{
            return redirect(route('admin.article.edit',$model)) -> withErrors(['error' => '更新失敗しました']);
        }       
    }

    /**
     * 削除機能
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 該当なデータを取得
        Article::find($id) -> delete();
        return ['status' => 0,'msg' => '削除しました'];        
    }
}
