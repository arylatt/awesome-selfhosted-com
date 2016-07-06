@extends('layouts.frontend')

@section('content')
	<p>
		Hello, {{ $user }}! 
		@if($user == "Anon")
			<a href="/login">Login</a>
		@endif
	</p>
@endsection