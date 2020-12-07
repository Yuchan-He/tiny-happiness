<?php

namespace App\Models;
// Authの機能を要れる、controllerの認証機能と関連する
use Illuminate\Foundation\Auth\User as Authenticatable;
// 論理削除(softdelete)を入れる
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    // 論理削除(softdelete)のtraitを入れる
    use SoftDeletes;

    // SoftDeletesのcolumnを指定する
    protected $dates = ['deleted_at'];

    // パスワードを非表示
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 关联角色表格查询
    public function role(){
        // User表は“belongs to”Role表 
        return $this -> belongsTo(Role::class,'role_id');
    }
}
