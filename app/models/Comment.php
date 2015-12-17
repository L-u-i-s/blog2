<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Comment extends Model{
	protected $table = 'comments';
	protected $fillable = ['author', 'email', 'message', 'status'];

	public $timestamps = false;

	public function posts(){
        $this->belongsTo('\App\Models\Post', 'comments', 'post_id')->where('status', 1);
    }
}