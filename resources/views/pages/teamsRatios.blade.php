@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">

			<div class="page-header">
				<h3>Teams Victory Ratios // {{ $season->name }}</h3>
			</div>

			<div class="ratios-table">
				<!-- <div class="ratio-body">  -->
				@forelse ($teams as $team)

					@if (!empty($time) && $time != $team->utc)
					</div>
					@endif

					@if ($loop->first)
					<div class="panel panel-default ratio-header">
						<div class="panel-heading">
							Ratios
						</div>
					@endif

						<div class="panel-body ratio-row">
							<div class="team">
								<img class="shield" src="{{ $team->shield }}">
								<div class="name">{{ $team->name }}</div>
							</div>

							<div class="ratio">
								{{ number_format((float)(100*$team->winner_matches/$team->all_matches), 1, '.', '') }}%
							</div>
						</div>

					@empty
						<p>No matches found.</p>

				@endforelse
				<!-- </div> -->
			</div>
		</div>
	</div>
</div>
@endsection