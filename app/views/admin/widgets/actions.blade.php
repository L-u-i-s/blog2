<!-- Entry Actions Well -->
<div class="well">
    <h4>Entry Actions</h4>
    
    <div class="list-group">
        <a href="{{$app->urlFor('admin.posts.index')}}" class="list-group-item" role="button">
            <span class="glyphicon glyphicon-th-list"></span> List
        </a>
        <a href="{{$app->urlFor('admin.posts.create')}}" class="list-group-item" role="button">
            <span class="glyphicon glyphicon-plus"></span> New
        </a>
        @if(isset($post))
            <a href="{{$app->urlFor('admin.posts.edit', ['id' => $post->id])}}" class="list-group-item" role="button">
                <span class="glyphicon glyphicon-pencil"></span> Edit
            </a>
            <a href="{{$app->urlFor('admin.posts.destroy', ['id' => $post->id])}}" class="list-group-item" role="button">
                <span class="glyphicon glyphicon-remove"></span> Remove
            </a>
        @endif
    </div>
</div>