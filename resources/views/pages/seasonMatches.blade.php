@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">

			<div class="page-header">
				<h3>Select a Season</h3>
			</div>
			<form>
				<label for="league">League</label>
				<select id="league", name="league">
					<option @if($league == 'bl1') selected="selected" @endif value="bl1">1st Fußball-Bundesliga</option>
					<option @if($league == 'bl2') selected="selected" @endif value="bl2">2nd Fußball-Bundesliga</option>
					<option @if($league == 'bl3') selected="selected" @endif value="bl3">3rd Fußball-Bundesliga</option>
				</select>

				<label for="year">Year</label>
				<select id="year", name="year">
					@for ($i = 2006; $i <= idate('Y'); $i++)
					<option @if($year == $i) selected="selected" @endif value="{{ $i }}">{{ $i }}</option>
					@endfor
				</select>
				<input type="submit" name="update" value="Update">
			</form>

			<div class="page-header">
				<h3>All Matches // {{ $season->name }}</h3>
			</div>

			@forelse ($matches as $match)

				@if (!empty($time) && $time != $match->timeUTC)
				</div>
				@endif

				@if (empty($time) || $time != $match->timeUTC)
				<div class="panel panel-default">
					<div class="panel-heading" style="text-align: center;">
						@datetime(new DateTime($match->timeUTC)) (UTC)
					</div>
				@endif

					<div class="panel-body" style="text-align: center;">
						{{ $match->team1->name }}&nbsp;<img height="20px" width="20px" src="{{ $match->team1->shield }}">
						&nbsp;<strong>&nbsp;vs&nbsp;</strong>&nbsp;
						<img height="20px" width="20px" src="{{ $match->team2->shield }}">&nbsp;{{ $match->team2->name }}
					</div>

				<?php $time = $match->timeUTC ?>

				@empty
					<p>No matches found.</p>

			@endforelse
			</div>

			{{ $matches->links() }}
		</div>
	</div>
</div>
@endsection