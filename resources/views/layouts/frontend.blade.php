<html>
	<head>
		<title>
			{{ $title or "Awesome-Selfhosted" }}
		</title>
		<link rel="stylesheet" type="text/css" href="/css/all.css" />
	</head>

	<body>
		@unless(isset($notopnav))
			@include('components.frontend.topnav')
		@endunless
		<div class="page">
			@yield('content')
		</div>
	</body>
	<script type="text/javascript" src="/js/all.js"></script>
</html>