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

			@forelse ($teams as $team)

				@if (!empty($time) && $time != $team->utc)
				</div>
				@endif

				@if (empty($time) || $time != $team->utc)
				<div class="panel panel-default">
					<div class="panel-heading" style="text-align: center;">
						@datetime(new DateTime($team->utc)) (UTC)
					</div>
				@endif

					<div class="panel-body" style="text-align: center;">
						<img height="20px" width="20px" src="{{ $team->shield }}">&nbsp;
						<strong>{{ $team->name }}</strong>
					</div>

				<?php $time = $team->utc ?>

				@empty
					<p>No matches found.</p>

			@endforelse
		</div>
	</div>
</div>
@endsection