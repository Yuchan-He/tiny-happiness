@extends('front.common.main')
@section('content')
   <div class="site-section bg-light">
      <div class="container">
        <div class="row">
          <div class="col-md-7 mb-5">
            <!-- 成功情報の表示 -->
            @include('front.common.msg')                      
            <form action="{{route('front.login')}}" method="post" class="p-5 bg-white">
            @csrf
            <!-- エラー情報の表示 -->
            @include('front.common.validate')                       
              <div class="row form-group">                
                <div class="col-md-12">
                  <label class="text-black" for="username">ユーザー名：</label> 
                  <input type="text" id="username" name="username" class="form-control" value="{{old('username')}}">
                </div>
              </div>
              <div class="row form-group">                
                <div class="col-md-12">
                  <label class="text-black" for="password">パスワード：</label> 
                  <input type="password" id="password" name="password" class="form-control">
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-12">
                  <input type="submit" value="ログイン" class="btn btn-primary py-2 px-4 text-white">
                </div>
              </div>  
            </form>
          </div>
          </div>
        </div>
      </div>
    </div>       
  </div>

  @endsection