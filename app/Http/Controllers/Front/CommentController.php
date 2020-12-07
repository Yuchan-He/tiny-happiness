<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;

class CommentController extends Controller
{

    /**
     * 文章の中に、コメントを提出して、反映する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //　データを検証する
        $this ->validate($request,
            ['comment' => 'required'      
            ]);
        // 保存しないデータを除外する        
        $post = $request -> except(['_token']);
        // DBに保存していく
        $comment = Comment::create($post);
    
        return redirect(route('front.article.index')) -> withErrors(['error' => '入力箇所を完了してください']);
    }

}
