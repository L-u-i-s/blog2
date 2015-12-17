<?php
namespace App;

use Slim\Slim as Slim;
use Slim\Middleware\SessionCookie as SessionCookie;
use Slim\Views\Blade as Blade;
use Respect\Validation\Exceptions\NestedValidationExceptionInterface as NestedValidationExceptionInterface;
use Respect\Validation\Validator as Validator;
use Illuminate\Database\Capsule\Manager as Capsule;
use Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware as WhoopsMiddleware;

use \App\Models\Post as Post;
use \App\Models\Comment as Comment;
use \App\Models\Lookup as Lookup;

class Blog{

	protected $app;

	public function __construct(){
		$this->app = new Slim([
			# Adds application settings
			'view' => new Blade(),
			'templates.path' => '../app/views'
		]);
		$this->app->add(new WhoopsMiddleware);
		$this->app->add(new SessionCookie());

		$views = $this->app->view();
		$views->parserOptions = [
			'debug' => true,
			'cache' => '../html_cache'
		];
		
		$capsule = new Capsule; 
		
		$capsule->addConnection([
		    'driver'    => 'sqlite',
		    'database'  => __DIR__.'/store/app.sqlite.db',
		    'prefix'   => '',
		]);
		
		$capsule->bootEloquent();

		# Redirects from / to /links route xD
		$this->app->get('/', function(){
			$this->app->redirect('/posts', 301);
		});

		# Redirects from / to /links route 
		$this->app->get('/admin', function(){
			$this->app->redirect('/admin/posts', 301);
		});

		# Defines a notFound error page
		$this->app->notFound(function(){
		    $this->app->render('errors.404');
		});
	}

	public function init(){
		# Front-Endd

		$this->app->get('/posts', function(){
			$posts = $this->getActivePosts();
			$this->app->render('posts.index', ['posts' => $posts, 'app' => $this->app]);
		})
		->name('posts.index');

		$this->app->get('/posts/:id', function($id){
			$post = $this->getPost($id);
			if ($post->status!=0)
			    $this->app->render('posts.show', ['post' => $post, 'app' => $this->app]);
			else
			    $this->app->notFound();
		})
		->name('posts.show')
		->conditions(['id' => '[0-9]+']);
		
		$this->app->post('/posts/:id/comments/', function($id){
			$params = $this->app->request->post();
			unset($params['save']);
			$post = $this->getPost($id);
			$this->storeComment($params, $id);
			$this->app->redirect($this->app->urlFor('posts.show', ['id' => $post->id]));
		})
		->name('posts.comments.store')
		->conditions(['id' => '[0-9]+']);

		# Back-End

		$this->app->get('/admin/posts', function(){
			$posts = $this->getPosts();
			$this->app->render('admin.posts.index', ['posts' => $posts, 'app' => $this->app]);
		})
		->name('admin.posts.index');

		$this->app->get('/admin/posts/:id', function($id){
			$post = $this->getPost($id);
			$this->app->render('admin.posts.show', ['post' => $post, 'app' => $this->app]);
		})
		->name('admin.posts.show')
		->conditions(['id' => '[0-9]+']);

		$this->app->get('/admin/posts/create', function(){
			$this->app->render('admin.posts.create', ['app' => $this->app]);
		})
		->name('admin.posts.create');
		
		$this->app->post('/admin/posts', function(){
			$params = $this->app->request->post();
			unset($params['save']);
			$post = $this->storePost($params);
			//dd($link);
			if($post)
				$this->app->redirect('/admin/posts');
			else
				$this->app->redirect('/admin/posts/create');
		})
		->name('posts.store');
		
		$this->app->get('/admin/posts/:id/edit', function($id){
			$post = $this->getPost($id);
			$post_statuses = $this->getStatuses('post.status');
			$this->app->render('admin.posts.edit', ['post' => $post, 'post_statuses' => $post_statuses, 'app' => $this->app]);
		})
		->name('admin.posts.edit')
		->conditions(['id' => '[0-9]+']);
		
		$this->app->post('/admin/posts/:id', function($id){
		    $post = $this->getPost($id);
			$params = $this->app->request->post();
			unset($params['save']);
			if($this->storePost($params, $post))
				$this->app->redirect('/admin/posts');
			else
				//en php el caracter punto "." se utiliza para concatenar, ahora, si no quieres intercalar variables en cadenas usanod punto podrias usar dobles comillas y lo que indicas en la linea 124 funcionaria, pero la mejor opcion es utilizar la funcion urlFor, quedaria así $this->app->redirect($this->app->urlFor('admin.posts.edit', ['id' => $this->id]))
				$this->app->redirect($this->app->urlFor('admin.posts.edit', ['id' => $post->id]));
		})
		->name('admin.posts.update')
		->conditions(['id' => '[0-9]+']);
		
		$this->app->get('/admin/posts/:post_id/comments/:comment_id/authorize', function($post_id, $comment_id){
			$post = $this->getPost($post_id);
			$this->authorizeComment($post_id, $comment_id);
			$this->app->redirect($this->app->urlFor('admin.posts.show', ['id' => $post->id]));
		})
		->name('admin.posts.comments.authorize')
		->conditions(['post_id' => '[0-9]+', 'comment_id' => '[0-9]+']);
		
		$this->app->get('/admin/posts/:id/delete', function($id){
			$post = $this->getPost($id);
			$post->delete();
			$this->app->redirect($this->app->urlFor('posts.store'));
		})
		->name('admin.posts.destroy')
		->conditions(['id' => '[0-9]+']);
		
		$this->app->get('/admin/posts/:post_id/comments/:comment_id/delete', function($post_id, $comment_id){
			$comment = Comment::where('post_id', $post_id)->find($comment_id);
			$post = $this->getPost($post_id);
			$comment->delete();
			$this->app->redirect($this->app->urlFor('admin.posts.show', ['id' => $post->id]));
		})
		->name('admin.posts.comments.destroy')
		->conditions(['id' => '[0-9]+']);
		
		//comentario del admin
		$this->app->post('/admin/posts/:id/comments', function($id){
			$params = $this->app->request->post();
			unset($params['save']);
			$post = $this->getPost($id);
			$this->storeAdminComment($params, $id);
			$this->app->redirect($this->app->urlFor('admin.posts.show', ['id' => $post->id]));
		})
		->name('admin.posts.comments.store')
		->conditions(['id' => '[0-9]+']);

		# start Slim
		$this->app->run();
	}
	
	public function getStatuses($type = 'post.status'){
		$statuses = Lookup::where('lookup.type', $type)->get();
		return $statuses;
	}
	
	public function authorizeComment($post_id, $comment_id){
		$comment = Comment::where('post_id', $post_id)->find($comment_id);
		//dd($comment);// a partir de aqui no se sigue ejecutando nada, y puedes ver que es lo que contiene la variable $comment, te puede servir para depurar, pero no olvides comentarla o quitarla mas tarde
		$comment->status = 1;
		$comment->save();
	}
	
	public function getActivePosts($limit = 10, $offset = 0){
		$posts = Post::whereStatus(1)->skip($offset)->take($limit)->with('comments', 'newComments', 'activeComments')->get();
		if(!$posts)
			$this->app->notFound();
		else
			return $posts;
	}
	
	public function getPosts($limit = 11, $offset = 0){
		$posts = Post::skip($offset)->take($limit)->with('comments', 'newComments', 'activeComments', 'postStatus')->get();
		if(!$posts)
			$this->app->notFound();
		else
			return $posts;
	}
	
	public function getPost($id){
		$post = Post::with('comments', 'activeComments', 'newComments')->find($id);
		if(!$post)
			$this->app->notFound();
		else
			return $post;
	}
	
	public function storePost($params, $post = null){
		if(!$post)
			$post = $this->newPost();
		$post->fill($params);
		if($this->validateInput($params) && $post->save()){
			//dd($post->toArray());
		    return $post;
		}
	    return false;
	}
	
	public function newPost(){
		return new Post;
	}
	
	public function storeComment($params, $id){
		$comment = $this->newComment();
		$comment->fill($params);
		$comment->status = 0;
		$comment->post_id = $id;
		if($this->validateComment($params))
		    $comment->save();
	}
	
	public function storeAdminComment($params, $id){
		$comment = $this->newComment();
		$comment->fill($params);
		$comment->status = 1;
		$comment->post_id = $id;
		if($this->validateAdminComment($params))
		    $comment->save();
	}
	
	public function newComment(){
		return new Comment;
	}
	
	public function validateInput($params){
		$postValidator = Validator::
			key('title', Validator::string()->length(3,64)->notEmpty())
			->key('description', Validator::string()->length(3,256)->notEmpty())
			->key('content', Validator::string()->length(3,65536)->notEmpty())
			->key('status', Validator::between(0,2, true));
		//dd($params);
		try{
			$postValidator->assert($params);
			return true;
		}
		catch(NestedValidationExceptionInterface $exception) {
			$messages = $exception->findMessages(['title', 'description', 'content', 'status']);
			//dd($messages);
			$this->app->flash('post.errors', $messages);
			return false;
		}
	}
	
	public function validateComment($params){
		$commentValidator = Validator::
			key('author', Validator::string()->length(2,128)->notEmpty())
			->key('email', Validator::string()->length(7,128)->notEmpty())
			->key('message', Validator::string()->length(1,8192)->notEmpty());
		//dd($params);
		try{
			$commentValidator->assert($params);
			return true;
		}
		catch(NestedValidationExceptionInterface $exception) {
			$messages = $exception->findMessages(['author', 'email', 'message']);
			//dd($messages);
			$this->app->flash('comment.errors', $messages);
			return false;
		}
	}
	
	public function validateAdminComment($params){
		$commentValidator = Validator::
			key('message', Validator::string()->length(1,8192)->notEmpty());
		//dd($params);
		try{
			$commentValidator->assert($params);
			return true;
		}
		catch(NestedValidationExceptionInterface $exception) {
			$messages = $exception->findMessages(['author', 'email', 'message']);
			//dd($messages);
			$this->app->flash('comment.errors', $messages);
			return false;
		}
	}
}