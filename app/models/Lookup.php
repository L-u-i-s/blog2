<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Lookup extends Model{
	protected $table = 'lookup';

	public $timestamps = true;
	
	/*public function Links(){
		$this->hasMany('\App\Models\Link', 'status', 'code')->where('lookup.type', 'link.status');
	}*/
}