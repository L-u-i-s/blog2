<!-- First Blog Post -->
<h2>
    <a href="#">{{$post->title}}</a>
</h2>
<p class="lead">
    by <a href="#">Post author</a>
</p>
<p><span class="glyphicon glyphicon-time"></span>Posted on August 24, 2013 at 9:00 PM</p>
<hr>
{{--<img class="img-responsive" src="http://placehold.it/900x300" alt="">--}}
<hr>
<p>{{$post->description}}</p>
<a class="btn btn-primary" href="/posts/{{$post->id}}">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>