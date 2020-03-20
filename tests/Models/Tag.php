<?php
namespace Kompo\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Kompo\Tests\Models\Post;

class Tag extends Model
{	
	
	public function posts() 
	{
		return $this->belongsToMany(Post::class); 
	}

}
