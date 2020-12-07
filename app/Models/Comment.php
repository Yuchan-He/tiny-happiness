<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Base
{
    // Comment表は“belongs to”Article表
    public function article(){
        return $this -> belongsTo(Article::class,'article_id');
    }

    // Comment表は“belongs to”User表
    public function user(){
        return $this -> belongsTo(User::class,'user_id');
    }
}
