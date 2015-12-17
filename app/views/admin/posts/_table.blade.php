<table class="table table-bordered table-hover table-striped">
	<thead>
		<tr>
			<th>Id</th>
			<th>Title</th>
			<th>Status</th>
			<th># Comments</th>
			<th>New comments</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	    @foreach($posts as $post)
			<tr>
				<td>{{$post->id}}</td>
				<td>{{$post->title}}</td>
				<td>{{$post->postStatus->value}}</td>
				<td>{{count($post->activeComments)}}</td>
				<td>{{count($post->newComments)}}</td>
				<td>
					<a href="{{$app->urlFor('admin.posts.show', ['id' => $post->id])}}" class="btn btn-primary btn-xs" role="button">
						<span class="glyphicon glyphicon-eye-open"></span>
					</a>
					<a href="{{$app->urlFor('admin.posts.edit', ['id' => $post->id])}}" class="btn btn-info btn-xs" role="button">
			            <span class="glyphicon glyphicon-pencil"></span>
			        </a>
					<a href="{{$app->urlFor('admin.posts.destroy', ['id' => $post->id])}}" class="btn btn-danger btn-xs" role="button">
						<span class="glyphicon glyphicon-trash"></span>
					</a>
				</td>
			</tr>
		@endforeach	
	</tbody>
</table>