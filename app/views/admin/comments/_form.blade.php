@if(isset($flash['post.errors']))
	@foreach($flash['post.errors'] as $error)
		<div class="alert alert-warning" role="alert">{{$error}}</div>
	@endforeach
@endif

<div class="well">
    <h4>Leave a Comment:</h4>
    <form action="{{$app->urlFor('admin.posts.comments.store', ['id' => $post->id])}}" method="POST" role="form" class="clearfix">
        <div class="form-group">
        	<textarea class="form-control" id="comment-message" rows="3" name="message" placeholder="Your message goes here.."></textarea>
        </div>
        <button type="submit" class="btn btn-primary pull-right">Submit</button>
    </form>
</div>