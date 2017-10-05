@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">

			<div class="page-header">
				<h3>Teams Victory Ratios // {{ $season->name }}</h3>
			</div>

			@forelse ($teams as $team)

				@if (!empty($time) && $time != $team->utc)
				</div>
				@endif

				@if ($loop->first)
				<div class="panel panel-default">
					<div class="panel-heading" style="text-align: center;">
						Ratios
					</div>
				@endif

					<div class="panel-body" style="text-align: center;">
						<img src="{{ $team->shield }}">&nbsp;
						<strong>{{ $team->name }}</strong>&nbsp;&nbsp;{{ number_format((float)(100*$team->winner_matches/$team->all_matches), 1, '.', '') }}%
					</div>

				@empty
					<p>No matches found.</p>

			@endforelse
		</div>
	</div>
</div>
@endsection