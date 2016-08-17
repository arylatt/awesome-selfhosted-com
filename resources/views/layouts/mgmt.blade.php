<html>
	<head>
		<title>
			@if(isset($title))
				{{ $title }} - Collab Panel - Awesome-Selfhosted
			@else
				Collab Panel - Awesome-Selfhosted
			@endif
		</title>
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Raleway" />
		<link rel="stylesheet" type="text/css" href="/css/mgmt.css" />
	</head>

	<body>
		@unless(isset($notopnav))
			@include('components.mgmt.topnav')
		@endunless
		<div class="page">
			@yield('content')
		</div>
	</body>
	<script type="text/javascript" src="/js/all.js"></script>
</html>