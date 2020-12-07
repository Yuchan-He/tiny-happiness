@extends('admin.common.main')
@section('css')
<!-- webuploaderのCSSを追加する -->
<link rel="stylesheet" type="text/css" href="/admin/lib/webuploader/0.1.5/webuploader.css">
@endsection
@section('content')

<article class="page-container">
	<form class="form form-horizontal" id="form-article-add">
		@csrf
		<!-- エラー情報の表示 -->
		@include('admin.common.validate')
		<!-- 投稿のユーザーID、非表示 -->
		<input type="hidden" name="user_id" value="{{auth() -> guard('admin')-> id()}}">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>タイトル：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" id="articletitle" name="title">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"></span>文章摘要：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea name="desn" cols="" rows="" class="textarea"  placeholder="" datatype="*10-100" dragonfly="true" nullmsg="備考をご記入ください！" onKeyUp="$.Huitextarealength(this,200)"></textarea>
				<p class="textarea-numberbar"></p>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">画像：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<!-- webuploader アップロードbtn -->
				<div id="uploader-demo">
				    <!--itemを保存する-->
				    <div id="fileList" class="uploader-list"></div>
				    <!--アップロードの画像の場所を非表示する-->
				    <input type="hidden" name="pic" id="pic" value=""/>

				    <div id="filePicker" name="file">画像選択</div>
				</div>
				<!-- webuploader アップロードbtn -->	
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>文章内容：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea name="body" cols="" rows="" class="textarea"  placeholder="何か書きましょう..." datatype="*10-100" dragonfly="true" nullmsg="備考をご記入ください" onKeyUp="$.Huitextarealength(this,200)"></textarea>
				<p class="textarea-numberbar"></p>
			</div>
		</div>

		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 投稿</button>
				<button class="btn btn-default radius" type="button"><a href="{{route('front.article.index')}}">&nbsp;&nbsp;キャンセル&nbsp;&nbsp;</a></button>
			</div>
		</div>
	</form>
</article>

@endsection
@section('js')
<script type="text/javascript" src="/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script> 
<script type="text/javascript" src="/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script> 
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/localization/messages_ja.js"></script>
<!--webuploaderのJSを追加する-->
<script type="text/javascript" src="/admin/lib/webuploader/0.1.5/webuploader.js"></script>
<script type="text/javascript">
$(function(){
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});
	
	//　フロント側のフォーム検証
	$("#form-article-add").validate({
		rules:{
			title:{
				required:true,
			},
			body:{
				required:true,
			}		
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			$(form).ajaxSubmit({
				type: 'post',
				url: "{{route('admin.article.store')}}" ,
				success: function(data){
					layer.msg('文章を追加しました!',{icon:1,time:2000},function(){
     				window.location = "{{route('front.article.index')}}";					
					});
				},
                error: function(XmlHttpRequest, textStatus, errorThrown){
					layer.msg('入力箇所を完了してください',{icon:2,time:3000},function(){
					
					});
				}
			});
		}
	});
	
	$list = $("#fileList"),
	$btn = $("#btn-star"),
	state = "pending",
	uploader;

	// Web Uploaderをインスタンス化
	var uploader = WebUploader.create({
		auto: true,
		swf: '/admin/lib/webuploader/0.1.5/Uploader.swf',
		server: "{{route('admin.article.webuploader')}}",
		// tokenを追加する
		formData:{

			_token:'{{csrf_token()}}'
		},				
		pick: '#filePicker',
		resize: false,
		accept: {
			title: 'Images',
			extensions: 'gif,jpg,jpeg,bmp,png',
			mimeTypes: 'image/*'
		}
	});
	uploader.on( 'fileQueued', function( file ) {
		var $li = $(
			'<div id="' + file.id + '" class="item thumbnail">' +
				'<div class="pic-box"><img></div>'+
				'<div class="info">' + file.name + '</div>' +
				'<p class="state">一生懸命アップロード中...</p>'+
			'</div>'
		),
		$img = $li.find('img');		
		// 複数の画像をアップロードするし際、古い画像を上書きする
		$('.thumbnail').remove();
		$list.append( $li );
		// thumbnailWidth x thumbnailHeight＝100 x 100
		uploader.makeThumb( file, function( error, src ) {
			if ( error ) {
				$img.replaceWith('<span>閲覧できません</span>');
				return;
			}
	
			$img.attr( 'src', src );
		}, 100, 100 );
	});
	uploader.on('uploadSuccess', function(file,response) {
		$( '#'+file.id ).addClass('upload-state-success').find(".state").text("アップロード成功しました");
		console.log(response.path);
		$('#pic').val(response.path);
	});
});
</script>

@endsection