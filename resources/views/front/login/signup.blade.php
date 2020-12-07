@extends('front.common.main')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')   
    <div class="site-section bg-light">
      <div class="container">
        <div class="row">
          <div class="col-md-7 mb-5">          
            <form action="{{route('front.signup')}}" method="post" class="p-5 bg-white">
            @csrf
            <!-- エラー情報の表示 -->
            @include('front.common.validate')            
              <div class="row form-group">                
                <div class="col-md-12">
                  <label class="text-black" for="username">ユーザー名：</label> <span id="info"></span>
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
                  <input type="submit" value="新規登録" class="btn btn-primary py-2 px-4 text-white">
                </div>
              </div>  
            </form>
          </div>
        </div>
      </div>
    </div>
        
  </div>
  @section('js')
  <script type="text/javascript">
    $(function () { 
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
      });

      var info = document.getElementById('info');
      $("#username").blur(function () {
        var username = $('#username').val();      
        $.ajax({          
          type: "post",
          url: "{{route('front.signupUsername')}}", 
          data: { 
            "username" : username,
          },
          dataType:"json",         
          success: function (data) {            
            console.log(data);
            info.innerHTML = "ユーザー名が使えます。";
            info.className = "text-black bg-success";
          },

          error: function (data) {
            console.log(data);
            info.innerHTML = "ユーザ名を再度入力ください、すでに登録されています。";
            info.className = "text-white bg-danger";
          },          

        });
      });
    });

  </script>

  @endsection
  @endsection