<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Post extends Model{
	protected $table = 'posts';
	protected $fillable = ['title', 'description', 'content', 'status'];
	public $timestamps = false;
	
	//obtiene todos los comentarios, sin importar el status
	public function comments(){
		return $this->hasMany('\App\Models\Comment', 'post_id', 'id');
	}
	
	//obtiene solo los comentarios activos
	public function activeComments(){
		return $this->hasMany('\App\Models\Comment', 'post_id', 'id')->where('status', 1);
	}
	
	//obtiene solo los comentarios nuevos
	public function newComments(){
		return $this->hasMany('\App\Models\Comment', 'post_id', 'id')->where('status', 0);
	}
	
	public function postStatus() {
        return $this->belongsTo('\App\Models\Lookup', 'status', 'code')->where('lookup.type', 'post.status');
    }
}