@extends('front.common.main')
  @section('content')
  
    <div class="site-cover site-cover-sm same-height overlay single-page" style="background-image: url('/storage/hero_1.jpg');">
      <div class="container">
        <div class="row same-height justify-content-center">
          <div class="col-md-12 col-lg-10">
            <div class="post-entry text-center">
              <h1 class="mb-4">Tiny Happiness</h1>
              <h2 class="mb-4">あなたの『小さいけれども、確かな幸せ』を
              <p>シェアしてみませんか</p></h2>
              <h3><font color="white">～自分の幸せを貯めつつ、周りに幸せを送る～</font></p></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="site-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-12">
            <h2>最新な投稿</h2>
          </div>
        </div>
        <div class="row">
          @foreach($data as $value)
          <div class="col-lg-4 mb-4">
            <div class="entry2">
              <a href="{{route('front.article.show',['id' => $value -> id])}}" target="_blank"><img src="{{$value -> pic}}" alt="Image" class="rounded" width="370" height="250" ></a>
              <div class="excerpt">
              <span class="post-category text-white mb-3 bg-success">{{$value -> user -> username}}</span>              
              <h2><a href="{{route('front.article.show',['id' => $value -> id])}}" target="_blank">{{$value -> title}}</a></h2>
              <div class="post-meta align-items-center text-left clearfix">
                <figure class="author-figure mb-0 mr-3 float-left"><img src="{{$value -> pic}}" alt="Image" class="img-fluid"></figure>
                <span>&nbsp;&nbsp; {{$value -> created_at}}</span>
              </div>              
                <p>{{$value -> desn}}</p>
                <p><a href="{{route('front.article.show',['id' => $value -> id])}}" target="_blank">Read More</a></p>
              </div>
            </div>
          </div>
          @endforeach
          {{ $data->links() }}
      </div>
    </div>

  @endsection