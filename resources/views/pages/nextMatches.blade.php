@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">

			<div class="page-header">
				<h3>Select the league</h3>
			</div>
			<form>
				<label for="league">League</label>
				<select id="league", name="league">
					<option @if($league == 'bl1') selected="selected" @endif value="bl1">1st Fußball-Bundesliga</option>
					<option @if($league == 'bl2') selected="selected" @endif value="bl2">2nd Fußball-Bundesliga</option>
					<option @if($league == 'bl3') selected="selected" @endif value="bl3">3rd Fußball-Bundesliga</option>
				</select>
				
				<input type="submit" name="update" value="Update">
			</form>

			<div class="page-header">
				<h3>Next Matches by Team // {{ $season->name }}</h3>
			</div>

			<div class="nextmatches-table">
			@forelse ($teams as $team)

				@if (!empty($time) && $time != $team->utc)
				</div>
				@endif

				@if (empty($time) || $time != $team->utc)
				<div class="panel panel-default">
					<div class="panel-heading nextmatch-header">
						@datetime(new DateTime($team->utc)) (UTC)
					</div>
				@endif

					<div class="panel-body nextmatch-row">
						<div class="team">
							<img class="shield" src="{{ $team->shield }}">
							<div class="name">{{ $team->name }}</div>
						</div>
					</div>

				<?php $time = $team->utc ?>

				@empty
					<p>No matches found.</p>

			@endforelse
			</div>
		</div>
	</div>
</div>
@endsection