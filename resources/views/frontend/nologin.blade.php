@extends('layouts.frontend')

@section('content')
	<div class="ui container">
		<div class="ui submit-nologin grid">
			<div class="four wide column">
				<img class="ui large centered image" src="/img/octocat.png" />
			</div>
			<div class="twelve wide column">
				<h1 class="ui header">
					Hold up a sec...
					<div class="sub header">
						I'm afraid I can't let you do that, Dave.
					</div>
				</h1>
				<p>You need to sign in before you can submit requests to this repository. Click the button below to sign in through GitHub.</p>
				<a href="/login?backTo=/submit" class="ui white button">
					<i class="github icon"></i>
					Sign In through GitHub
				</a>
			</div>
		</div>
	</div>
@endsection