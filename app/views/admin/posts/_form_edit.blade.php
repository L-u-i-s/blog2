@if(isset($flash['post.errors']))
	@foreach($flash['post.errors'] as $error)
		<div class="alert alert-warning" role="alert">{{$error}}</div>
	@endforeach
@endif

<form action="{{$app->urlFor('admin.posts.update', ['id' => $post->id])}}" method="POST">
    <div class="form-group">
        <label for="post-title">Post Title</label>
        <input type="text" class="form-control" id="post-title" placeholder="Post title" name="title" value="{{$post->title}}">
    </div>
    <div class="form-group">
        <label for="post-description">Post Description</label>
        <textarea class="form-control" id="post-description" cols="30" rows="2" name="description" placeholder="Some post description">{{$post->description}}</textarea>
    </div>
    <div class="form-group">
        <label for="post-content">Post Content</label>
        <textarea class="form-control" id="post-content" cols="30" rows="8" name="content" placeholder="The main post content">{{$post->content}}</textarea>
    </div>
    <div class="form-group">
        <label for="post-status">Post Status</label>
        <select class="form-control" id="post-status" name="status">
            @foreach($post_statuses as $post_status)
				@if($post_status->code == $post->postStatus->code)
					<option value="{{$post_status->code}}" selected="selected">{{ucfirst($post_status->value)}}</option>
				@else
					<option value="{{$post_status->code}}">{{ucfirst($post_status->value)}}</option>
				@endif
			@endforeach   
        </select>
    </div>
    <button type="submit" class="btn btn-default pull-right">Submit</button>
</form>