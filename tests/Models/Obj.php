<?php
namespace Kompo\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Kompo\Tests\Models\File;
use Kompo\Tests\Models\Place;
use Kompo\Tests\Models\User;

class Obj extends Model
{
	protected $casts = [
		'tags_cast' => 'array',
		'file_cast' => 'array',
		'files_cast' => 'array',
		'place_cast' => 'array',
		'places_cast' => 'array'
	];

	/**************************************************************** 
	 ************** BELONGS *****************************************
	 ***************************************************************/

	public function belongsToPlain() //could'nt use belongsTo because reserved
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function belongsToOrdered() //even though doesn't make sense, helps prove they don't mess with the relationship
	{
		return $this->belongsTo(User::class, 'user_id')->orderBy('name');
	}

	public function belongsToFiltered() //even though doesn't make sense, helps prove they don't mess with the relationship
	{
		return $this->belongsTo(User::class, 'user_id')->where('name', '<', 'm');
	}

	public function belongsToManyPlain()
	{
		return $this->belongsToMany(File::class);
	}

	public function belongsToManyOrdered()
	{
		return $this->belongsToMany(File::class)->orderBy('name');
	}

	public function belongsToManyFiltered()
	{
		return $this->belongsToMany(File::class)->where('name', '<', 'm');
	}

	/************************************************************** 
	 ********************* HAS ************************************
	 *************************************************************/

	public function hasOnePlain()
	{
		return $this->hasOne(File::class);
	}

	public function hasOneOrdered()
	{
		return $this->hasOne(File::class)->orderBy('name');
	}

	public function hasOneFiltered()
	{
		return $this->hasOne(File::class)->where('order', 1);
	}

	public function hasManyPlain()
	{
		return $this->hasMany(File::class);
	}

	public function hasManyOrdered()
	{
		return $this->hasMany(File::class)->orderBy('name');
	}

	public function hasManyFiltered()
	{
		return $this->hasMany(File::class)->where('order', 1);
	}

	public function hasOnePlain2()
	{
		return $this->hasOne(Place::class);
	}
	
	public function hasOneOrdered2()
	{
		return $this->hasOne(Place::class)->orderBy('address');
	}

	public function hasOneFiltered2()
	{
		return $this->hasOne(Place::class)->where('order', 1);
	}

	public function hasManyPlain2()
	{
		return $this->hasMany(Place::class);
	}

	public function hasManyOrdered2()
	{
		return $this->hasMany(Place::class)->orderBy('address');
	}

	public function hasManyFiltered2()
	{
		return $this->hasMany(Place::class)->where('order', 1);
	}



	/**************************************************************** 
	 ****** MorphsOne/Many and inverse MorphTo **********************
	 ***************************************************************/

	public function morphOnePlain()
	{
		return $this->morphOne(File::class, 'model');
	}

	public function morphOneOrdered()
	{
		return $this->morphOne(File::class, 'model')->orderBy('name');
	}

	public function morphOneFiltered()
	{
		return $this->morphOne(File::class, 'model')->where('order', 1);
	}

	public function morphManyPlain()
	{
		return $this->morphMany(File::class, 'model');
	}

	public function morphManyOrdered()
	{
		return $this->morphMany(File::class, 'model')->orderBy('name');
	}

	public function morphManyFiltered()
	{
		return $this->morphMany(File::class, 'model')->where('order', 1);
	}

	public function morphOnePlain2()
	{
		return $this->morphOne(Place::class, 'model');
	}

	public function morphOneOrdered2()
	{
		return $this->morphOne(Place::class, 'model')->orderBy('address');
	}

	public function morphOneFiltered2()
	{
		return $this->morphOne(Place::class, 'model')->where('order', 1);
	}

	public function morphManyPlain2()
	{
		return $this->morphMany(Place::class, 'model');
	}

	public function morphManyOrdered2()
	{
		return $this->morphMany(Place::class, 'model')->orderBy('address');
	}

	public function morphManyFiltered2()
	{
		return $this->morphMany(Place::class, 'model')->where('order', 1);
	}

	//For morphTo, to load the options, we need to know the Model they relate to...
	public function morphToPlain()
	{
		return $this->morphTo(null, 'model_type', 'model_id');
	}

	//public function morphToPlain(){ return $this->morphTo('model'); } 
	//NOTE: this is wrong, it assigns the relations in ->model instead of ->morphToPlain!! 

	public function morphToOrdered()
	{
		return $this->morphTo(null, 'model_type', 'model_id')->orderBy('name');
	}

	public function morphToFiltered()
	{
		return $this->morphTo(null, 'model_type', 'model_id')->where('name', '<', 'm');
	}



	/***************************************************************************************************** 
	 ************* MANY to MANY MORPHS (MorphToMany vs MorphedByMany) ************************************
	 ****************************************************************************************************/


	public function morphToManyPlain()
	{
		return $this->morphToMany(File::class, 'model', 'file_obj'); //file_id links to file
	}

	public function morphToManyOrdered()
	{
		return $this->morphToMany(File::class, 'model', 'file_obj')->orderBy('name'); //file_id links to file
	}

	public function morphToManyFiltered()
	{
		return $this->morphToMany(File::class, 'model', 'file_obj')->where('name', '<', 'm'); //file_id links to file
	}

	public function morphedByManyPlain()
	{
		return $this->morphedByMany(File::class, 'model', 'file_obj'); //obj_id is the link here
	}

	public function morphedByManyOrdered()
	{
		return $this->morphedByMany(File::class, 'model', 'file_obj')->orderBy('name'); //obj_id is the link here
	}

	public function morphedByManyFiltered()
	{
		return $this->morphedByMany(File::class, 'model', 'file_obj')->where('name', '<', 'm'); //obj_id is the link here
	}

}
