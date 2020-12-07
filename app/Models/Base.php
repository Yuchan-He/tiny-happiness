<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
// 論理削除を追加
use Illuminate\Database\Eloquent\SoftDeletes;

class Base extends Model
{
	// 論理削除のtrait追加
	use SoftDeletes;
	// 論理削除のコラムを設定
	protected $datas = ['deleted_at'];
}

