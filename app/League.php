<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
	protected $fillable = ['id', 'league', 'year', 'name'];

	public function matches() {
		return $this->hasMany('App\Match');
	}
}