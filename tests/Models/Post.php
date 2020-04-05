<?php

namespace Kompo\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\PostTag;
use Kompo\Tests\Models\Tag;
use Kompo\Tests\Models\User;

class Post extends Model
{	
	public function tags() 
	{
		return $this->belongsToMany(Tag::class); 
	}

	public function obj() 
	{
		return $this->hasOne(Obj::class); 
	}

	public function postTag() 
	{
		return $this->hasOne(PostTag::class); 
	}

	public function objs() 
	{
		return $this->hasMany(Obj::class); 
	}

	public function author()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

}
