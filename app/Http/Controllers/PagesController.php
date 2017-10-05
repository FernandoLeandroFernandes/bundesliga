<?php

namespace App\Http\Controllers;

use \Datetime;
//use \Debugbar;

use App\League;
use App\Team;
use App\Match;
use App\Sync;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller {

	private function loadSeason($league, $year) {

		// Debugbar::info('Loading SEASONS... ');

		$season = League::firstOrCreate([
			'league'=> $league,
			'year'	=> $year
		]);

		if ($this->synchronize($season)) {

			$season = League::firstOrCreate([
				'league'=> $league,
				'year'	=> $year
			]);
		}

		return $season;
	}

	private function synchronize($season) {

		// ob_start();
		// var_dump($season);
		// Debugbar::info('$season: '.ob_get_clean());

		// Debugbar::info('empty($season->sync): '.empty($season->sync));
		// Debugbar::info('is_null($season->sync): '.is_null($season->sync));

		// if there's an already synced season on record...
		if (!is_null($season->sync)) {

			$openMatch = Match::where('league_id', $season->id)
							  ->where('finished', 0)
							  ->orderBy('timeUTC')
							  ->first();
	
			// ob_start();
			// var_dump($openMatch);
			// Debugbar::info('$openMatch: '.ob_get_clean());

			if (empty($openMatch)) return false;

			$matchTime = DateTime::createFromFormat('Y-m-d G:i:s', $openMatch->timeUTC);

			// Debugbar::info('$matchTime < new Datetime(): '.($matchTime < new Datetime() ? 'true' : 'false'));

			if ($matchTime > new Datetime()) return false;

				// $url = 'https://www.openligadb.de/api/getcurrentgroup/'.$season->league;
				// $response = \Httpful\Request::get($url)->send();
				// $gameDay = ($response->body->GroupOrderID);

				// $url = 'https://www.openligadb.de/api/getlastchangedate/'.$season->league.'/'.$season->year.'/'.$gameDay;
				// $response = \Httpful\Request::get($url)->send();
				// $lastChange = DateTime::createFromFormat('Y-m-d\TG:i:s.u', $response->body);

				// exit if the current update is greater than the last sync
				// if ($lastChange < $season->sync) return;

		}

		// Debugbar::info('Synchronizing data for league('.$season->league.') and year('.$season->year.')... ');

		// synchronize data
		$this->synchronizeTeams($season->league, $season->year);
		$this->synchronizeMatches($season->league, $season->year);

		return true;
	}

	private function synchronizeTeams($league, $year) {
		
		// Debugbar::info('Synchronizing TEAMS for league('.$league.') and year('.$year.')... ');

		$url = 'https://www.openligadb.de/api/getavailableteams/'.$league.'/'.$year;
		// Debugbar::info('Synchronizing TEAMS URL: ['.$url.']');

		$response = \Httpful\Request::get($url)->send();

		$teams = ($response->body);

		foreach ($teams as $teamData) {
			
			$team = Team::firstOrCreate(
				[ 'id' => $teamData->TeamId ],
				[
				'shortName' => $teamData->ShortName,
				'name' => $teamData->TeamName,
				'shield' => $teamData->TeamIconUrl
				]
			);

		}
	}

	private function synchronizeMatches($league, $year) {
	
		// Debugbar::info('Synchronizing MATCHES for league('.$league.') and year('.$year.')... ');

		$url = 'https://www.openligadb.de/api/getmatchdata/'.$league.'/'.$year;

		$response = \Httpful\Request::get($url)->send();

		$matches = ($response->body);

		foreach ($matches as $matchData) {

			if (!isset($thisLeague)) {

				$thisLeague = League::find($matchData->LeagueId);

				if (is_null($thisLeague) || is_null($thisLeague->id) || is_null($thisLeague->sync)) {

					League::
						  where('league', $league)
						->where('year', $year)
						->update([
							'id' => $matchData->LeagueId, 
							'name' => $matchData->LeagueName,
							'sync' => new Datetime()
						]);

				}
			}

			$winnerTeam = NULL;
			$matchScore = end($matchData->Goals);
			if (!empty($matchScore)) {
				if ($matchScore->ScoreTeam1 != $matchScore->ScoreTeam2) {
					$winnerTeam = $matchScore->ScoreTeam1 > $matchScore->ScoreTeam2 ? 
										$matchData->Team1->TeamId : 
										$matchData->Team2->TeamId;
				}
			}

			$thisMatch = Match::updateOrCreate(
				[ 'id' => $matchData->MatchID ],
				[
				'league_id'	 => $matchData->LeagueId,
				'timeUTC'	 => (new DateTime($matchData->MatchDateTimeUTC)),
				'finished'	 => $matchData->MatchIsFinished,
				'team1_id'	 => $matchData->Team1->TeamId,
				'team2_id'	 => $matchData->Team2->TeamId,
				'scoreTeam1' => ($matchScore ? $matchScore->ScoreTeam1 : 0),
				'scoreTeam2' => ($matchScore ? $matchScore->ScoreTeam2 : 0),
				'winnerTeam' => $winnerTeam
				]
			);
		}
	}

	public function about() {
		return view('pages.about');
	}

	public function index() {
		return view('pages.index');
	}

	public function seasonMatchesJSON() {
		$this->synchronizeData();
		return Response::json(Match::get());
	}

	public function seasonMatches(Request $request) {

		// Debugbar::info('[ seasonMatches ]');

		$league = $request->input('league', 'bl1');
		$year	= $request->input('year', idate('Y'));
		
		$season = $this->loadSeason($league, $year);

		$matches = $season->matches()->paginate(10);

		return view('pages.seasonMatches', compact('league', 'year', 'season', 'matches'));
	}

	public function nextMatches(Request $request) {


		// Debugbar::info('[ nextMatches ]');

		$league = $request->input('league', 'bl1');
		$currentYear = idate('Y');

		$season = $this->loadSeason($league, $currentYear);

		$teams = DB::select('
			select teams.*, min(matches.timeUTC) as utc 
			from "teams" 
			left join "matches" on ("teams"."id" = "matches"."team1_id" OR "teams"."id" = "matches"."team2_id")
			where "matches"."league_id" = '.$season->id.' and "matches"."finished" = 0 
			group by "teams"."id" 
			order by "utc"');

		// return Response::json(Match::get());
		return view('pages.nextMatches', compact('league', 'year', 'season', 'teams'));
	}

	public function teamsRatios(Request $request) {

		// Debugbar::info('[ teamsRatios ]');

		$league = $request->input('league', 'bl1');
		$year	= $request->input('year', idate('Y'));

		$season = $this->loadSeason($league, $year);

		// Debugbar::info('Loading TEAMS... ');

		$teams = DB::select('
			select tm.*, count(distinct(am.id)) as all_matches, count(distinct(wm.id)) as winner_matches
			from  teams as tm, matches as am, matches as wm
			where ((tm.id = am.team1_id or tm.id = am.team2_id) and (am.finished = 1))
			   AND ((tm.id = wm.winner_team_id) and (wm.finished = 1))
			group by tm.id;');

		// ob_start();
		// var_dump($teams);
		// Debugbar::info('$teams: '.ob_get_clean());

		// return Response::json($teams);
		return view('pages.teamsRatios', compact('league', 'year', 'season', 'teams'));
	}

}