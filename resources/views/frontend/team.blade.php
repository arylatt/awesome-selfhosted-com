@extends('layouts.frontend')

@section('content')
	<div class="ui container">
		<h1 class="ui header">
			The Team
		</h1>
		<p>Awesome-Selfhosted is maintained and organised by a group of individuals who share a passion for selfhosting and open source software. Find out more below.</p>
		<div class="ui four cards">
		@foreach($collabs as $collab)
			<div class="ui card">
				<div class="image">
					<img src="{{ $collab->Avatar() }}" />
				</div>
				<div class="content">
					<div class="header">
						{{ $collab->user_name }}
					</div>
					<div class="meta">
						{{ $collab->user }}
					</div>
					<div class="description">
						{!! $collab->StaffBio() !!}
					</div>
				</div>
				<div class="extra content">
					<a href="{{ $collab->github_url }}" target="_blank">
						{{ $collab->github_url }}
					</a>
				</div>
			</div>
		@endforeach
		</div>
		<h2 class="ui header">Comments, Suggestions or Feedback</h2>
		<p>Want to leave a comment, suggestion or any other feedback about Awesome-Selfhosted? Get in touch with us by filling out the form below.</p>
		<div class="ui form">
			<div class="two fields">
				<div class="field">
					<label for="name">Your Name</label>
					<input type="text" id="name" placeholder="Your Name" required />
				</div>
				<div class="field">
					<label for="email">Your E-Mail</label>
					<input type="email" id="email" placeholder="Your E-Mail" required />
				</div>
			</div>
			<div class="field">
				<label for="subject">Subject</label>
				<input type="text" id="subject" placeholder="Subject" required />
			</div>
			<div class="field">
				<label for="body">Body</label>
				<textarea id="body" placeholder="Your comment/suggestion/feedback here."></textarea>
			</div>
			<div class="right aligned field">
				<div class="ui green button">
					<i class="send icon"></i>
					Send Message
				</div>
			</div>
		</div>
	</div>
@endsection