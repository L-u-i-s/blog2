@if(isset($flash['comment.errors']))
	@foreach($flash['comment.errors'] as $error)
		<div class="alert alert-warning" role="alert">{{$error}}</div>
	@endforeach
@endif

<div class="well">
    <h4>Leave a Comment:</h4>
    <form action="{{$app->urlFor('posts.comments.store', ['id' => $post->id])}}" method="POST" role="form" class="clearfix">
    	<div class="form-group">
	        <input type="text" class="form-control" id="comment-author" placeholder="Author" name="author">
	    </div>
	    <div class="form-group">
	        <input type="text" class="form-control" id="comment-email" placeholder="We need your email" name="email">
	    </div>
        <div class="form-group">
        	<textarea class="form-control" id="comment-message" rows="3" name="message" placeholder="Your message goes here.."></textarea>
        </div>
        <button type="submit" class="btn btn-primary pull-right">Submit</button>
    </form>
</div>