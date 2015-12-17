@extends('admin.layouts.2-columns')


@section('content')
	<!-- Blog Post -->

	@include('posts.partials._article', compact('post'))

	<!-- Blog Comments -->

	@include('admin.comments._form')

	<hr>

	<!-- Posted Comments -->

	@foreach($post->comments as $comment)
		@include('admin.comments._item', compact('comment'))
	@endforeach

	{{--@include('comments._item_nested')--}}

@stop