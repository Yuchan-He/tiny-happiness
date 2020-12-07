<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * home page表示
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $data = Article::orderBy('updated_at','desc') -> paginate(6);
        return view('front.index.index',compact('data'));

    }

    /**
     * 記事の詳細を表示する
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $model = $article;
        // 文章のIDを取得
        $article_id = $article -> id; 
        // 文章のすべてのコメントを取得               
        $comment = Comment::where('article_id','=',$article_id) -> get();
        // 文章のすべてのコメントしたユーザーのidを取得
        $user_id = Comment::where('article_id','=',$article_id) -> pluck('user_id') -> toArray();

        return view('front.index.single', compact('model','comment'));        
    }

}
