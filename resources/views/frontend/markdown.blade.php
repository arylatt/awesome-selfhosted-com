@extends('layouts.frontend')

@section('content')
	<div class="ui container">
		<h1 class="ui header">
			Markdown
		</h1>
		<p>Have some Markdown</p>
		<div class="ui divider"></div>
		{!! $md !!}
	</div>
@endsection