<?php

namespace App;

use \DateTime;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
	protected $fillable = [
		'id',
		'league_id',
		'team1_id',
		'team2_id',
		'scoreTeam1',
		'scoreTeam2',
		'winner_team_id',
		'timeUTC',
		'finished'
	];

	public function team1() {
		return $this->belongsTo('App\Team');
	}

	public function team2() {
		return $this->belongsTo('App\Team');
	}

	public function winnerTeam() {
		return $this->belongsTo('App\Team', 'winner_team_id');
	}


}
