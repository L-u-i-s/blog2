<!-- Comment -->
<div class="media" style="padding-bottom: 30px;">
    <a class="pull-left" href="#">
        <img class="media-object" src="http://placehold.it/64x64" alt="">
    </a>
    <div class="media-body clearfix">
        <h4 class="media-heading">Start Bootstrap
            <div class="btn-group pull-right" role="group">
                @if($comment->status == 0)
                    <a href="{{$app->urlFor('admin.posts.comments.authorize', ['post_id' => $post->id, 'comment_id' => $comment->id])}}" class="btn btn-success btn-xs pull-right" role="button">
                        <span class="glyphicon glyphicon-ok"></span>
                    </a>
                @endif
                <a href="{{$app->urlFor('admin.posts.comments.destroy', ['post_id' => $post->id, 'comment_id' => $comment->id])}}" class="btn btn-danger btn-xs pull-right" role="button" onclick="return confirm('Do you really want to delete this comment?')">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            </div>
            <small>August 25, 2014 at 9:30 PM</small>
        </h4>
        {{$comment->message}} 
    </div>
</div>