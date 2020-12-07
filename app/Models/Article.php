<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Base
{

    protected $fillable = ['user_id'];

    // Article表は“belongs to”User表 
    public function user(){
        return $this -> belongsTo(User::class,'user_id');
    }

    

}
