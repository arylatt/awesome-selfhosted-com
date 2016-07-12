<html>
	<head>
		<title>
			{{ $title or "Awesome-Selfhosted" }}
		</title>
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto" />
		<link rel="stylesheet" type="text/css" href="/css/all.css" />
	</head>

	<body>
		@unless(isset($notopnav))
			@include('components.frontend.topnav')
		@endunless
		<div class="page">
			@yield('content')
		</div>
		@unless(isset($notopnav))
			<footer class="ui fluid attached segment">
				@include('components.frontend.footer')
			</footer>
		@endunless
	</body>
	<script type="text/javascript" src="/js/all.js"></script>
</html>