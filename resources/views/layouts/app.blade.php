<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ config('app.name', 'Bundesliga') }}</title>

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

		<!-- Styles -->
		<link href="/css/app.css" rel="stylesheet">

		<!-- Scripts -->
		<script>
				window.Laravel = {!! json_encode([
						'csrfToken' => csrf_token(),
				]) !!};
		</script>
</head>
<body>
		<div id="app">
				<nav class="navbar navbar-default navbar-static-top">
						<div class="container">
								<div class="navbar-header">

										<!-- Collapsed Hamburger -->
										<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
												<span class="sr-only">Toggle Navigation</span>
												<span class="icon-bar"></span>
												<span class="icon-bar"></span>
												<span class="icon-bar"></span>
										</button>

										<!-- Branding Image -->
										<a class="navbar-brand" href="{{ url('/') }}">
												{{ config('app.name', 'Laravel') }}
										</a>
								</div>

								<div class="collapse navbar-collapse" id="app-navbar-collapse">
										<!-- Left Side Of Navbar -->
										<ul class="nav navbar-nav">
												&nbsp;
										</ul>

										<!-- Right Side Of Navbar -->
										<ul class="nav navbar-nav navbar-right">
											<li><a href="{{ url('/seasonMatches') }}">All Matches</a></li>
											<li><a href="{{ url('/nextMatches') }}">Next Matches</a></li>
											<li><a href="{{ url('/teamsRatios') }}">Teams Victory Ratios</a></li>
											<li><a href="{{ url('/about') }}">About</a></li>
										</ul>
								</div>
						</div>
				</nav>

				@yield('content')

		</div>

		<!-- Scripts -->
		<script src="/js/app.js"></script>
</body>
</html>