<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
	/**
	* ページ数を設定する
    * @param 
    * @return ディフォルトのページ数
    */
   	protected $pagesize;
	public function __construct() {

		$page=$this -> pagesize = config('page.pagesize');

	}
}
