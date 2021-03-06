{{csrf_field()}}
<div class="layui-form-item">
	<label for="" class="layui-form-label">分类</label>
	<div class="layui-input-inline">
		<select name="category_id">
			<option value="0"></option>
			@foreach($categories as $category)
				<option value="{{ $category->id }}"
				        @if(isset($article->category_id)&&$article->category_id==$category->id)selected @endif >{{ $category->name }}</option>
				@if(isset($category->allChilds)&&$category->allChilds->isNotEmpty())
					@foreach($category->allChilds as $child)
						<option value="{{ $child->id }}"
						        @if(isset($article->category_id)&&$article->category_id==$child->id)selected @endif >
							&nbsp;&nbsp;&nbsp;┗━━{{ $child->name }}</option>
						@if(isset($child->allChilds)&&$child->allChilds->isNotEmpty())
							@foreach($child->allChilds as $third)
								<option value="{{ $third->id }}"
								        @if(isset($article->category_id)&&$article->category_id==$third->id)selected @endif >
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━━{{ $third->name }}</option>
							@endforeach
						@endif
					@endforeach
				@endif
			@endforeach
		</select>
	</div>
</div>
<div class="layui-form-item">
	<label for="" class="layui-form-label">标签</label>
	<div class="layui-input-block">
		@foreach($tags as $tag)
			<input type="checkbox" name="tags[]" {{ $tag->checked??'' }} value="{{ $tag->id }}"
			       title="{{ $tag->name }}">
		@endforeach
	</div>
</div>

<div class="layui-form-item">
	<label for="" class="layui-form-label">标题</label>
	<div class="layui-input-block">
		<input type="text" name="title" value="{{$article->title??old('title')}}" lay-verify="required"
		       placeholder="请输入标题" class="layui-input">
	</div>
</div>

<div class="layui-form-item">
	<label for="" class="layui-form-label">关键词</label>
	<div class="layui-input-block">
		<input type="text" name="keywords" value="{{$article->keywords??old('keywords')}}" placeholder="请输入关键词"
		       class="layui-input">
	</div>
</div>

<div class="layui-form-item">
	<label for="" class="layui-form-label">描述</label>
	<div class="layui-input-block">
		<textarea name="description" placeholder="请输入描述"
		          class="layui-textarea">{{$article->description??old('description')}}</textarea>
	</div>
</div>

<div class="layui-form-item">
	<label for="" class="layui-form-label">点击量</label>
	<div class="layui-input-block">
		<input type="number" name="click" value="{{$article->click??mt_rand(100,500)}}" class="layui-input">
	</div>
</div>
<div class="layui-form-item">
	<label for="" class="layui-form-label">缩略图</label>
	<div class="layui-input-block">
		<div class="layui-upload">
			<button type="button" class="layui-btn layui-btn-sm uploadPic"><i class="layui-icon">&#xe67c;</i>点击上传
			</button>
			<span class="layui-word-aux">支持 jpg/png/gif/bmp/jpeg 格式的图片</span>
			<div class="layui-progress" style="margin-top: 20px;" lay-filter="progress" lay-showPercent="true">
				@if(isset($article->thumb) || old('thumb') )
					<div id="progress_bar" class="layui-progress-bar layui-bg-green" lay-percent="100%"></div>
				@else
					<div id="progress_bar" class="layui-progress-bar layui-bg-red" lay-percent="0%"></div>
				@endif
			</div>
			<div class="layui-upload-list">
				<ul class="layui-upload-box layui-clear">
					@if(isset($article->thumb) || old('thumb'))
						<li><img src="{{ $article->thumb??old('thumb') }}" class="img"/>
							<p>上传成功</p></li>
					@endif
				</ul>
				<input type="hidden" name="thumb" class="layui-upload-input"
				       value="{{ $article->thumb??old('thumb') }}">
			</div>
		</div>
	</div>
</div>

<div class="layui-form-item">
	<label for="" class="layui-form-label">上传组图</label>
	<div class="layui-upload">
		<button type="button" class="layui-btn layui-btn-sm layui-bg-blue" id="uploadMultiplePic">多图选择</button>
		<span class="layui-word-aux">支持 jpg/png/gif/bmp/jpeg 格式的图片,最多 3 张</span>
		<button type="button" class="layui-btn layui-btn-sm" id="imgBeginUpload"><i class="layui-icon">&#xe67c;</i>开始上传
		</button>
		<div class="layui-input-block layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
			预览图：
			<div class="layui-upload-list">
				<ul class="layui-upload-box layui-clear" id="multiple-pic-preview">
					@if(old('images'))
						@foreach(old('images') as $key => $image)
							<li id="li-upload-img-{{$key}}">
								<img src="{{ $image }}" class="img"/>
								<p>上传成功
									<button type="button" class="test-delete layui-bg-red"
									        onclick="deleteUploadPic('li-upload-img-{{$key}}','{{$image}}')">删除
									</button>
								</p>
								<input type="hidden" name="images[]" class="layui-upload-input" value="{{ $image }}">
							</li>
						@endforeach
					@else
						@if (isset($article->images))
							@foreach($article->images as $key => $image)
								<li id="li-upload-img-{{$key}}">
									<img src="{{ $image }}" class="img"/>
									<p>上传成功
										<button type="button" class="test-delete layui-bg-red"
										        onclick="deleteUploadPic('li-upload-img-{{$key}}','{{$image}}')">删除
										</button>
									</p>
									<input type="hidden" name="images[]" class="layui-upload-input" value="{{ $image }}">
								</li>
							@endforeach
						@endif
					@endif
				</ul>
			</div>
		</div>
		{{--        <input type="hidden" name="images" class="layui-upload-input" value="{{ $article->images??'' }}">--}}
		{{--        <input type="hidden" name="images[]" class="layui-upload-input" value="">--}}
	</div>
</div>

<div class="layui-form-item">
	<label for="" class="layui-form-label">内容</label>
	<div class="layui-input-block">
		<script id="container" name="content" type="text/plain" style="width: 100%">
			{!! $article->content??old('content') !!}
		</script>
	</div>
</div>

<div class="layui-form-item">
	<label for="" class="layui-form-label">外链</label>
	<div class="layui-input-block">
		<input type="text" name="link" value="{{$article->link??''}}" placeholder="资讯外部链接，可为空" class="layui-input">
	</div>
</div>

<div class="layui-form-item">
	<div class="layui-input-block">
		<button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
		<a class="layui-btn" href="{{route('admin.article')}}">返 回</a>
	</div>
</div>
