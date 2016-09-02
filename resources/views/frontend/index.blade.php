@extends('layouts.frontend')

@section('content')
	<div class="ui container">
		<div class="ui grid">
			<div class="row">
				<div class="ui center aligned column">
					<h1 class="ui header">
						Awesome-Selfhosted
					</h1>
					<p>We have curated a community driven directory of selfhosted services, and are working to enable communication withn the selfhosted community.</p>
				</div>
			</div>
			<div class="ui divider"></div>
			<div class="five column centered row">
				<div class="column">
					<a href="/view/markdown">
						<i class="massive black browser icon"></i>
						<h3 class="ui center aligned header">
							View Our List
						</h3>
					</a>
				</div>
				<div class="column">
					<a href="/submit">
						<i class="massive black edit icon"></i>
						<h3 class="ui center aligned header">
							Contribute
						</h3>
					</a>
				</div>
				<div class="column">
					<a href="/sponsors">
						<i class="massive black users icon"></i>
						<h3 class="ui center aligned header">
							Our Sponsors
						</h3>
					</a>
				</div>
				<div class="column">
					<a href="/about">
						<i class="massive black help icon"></i>
						<h3 class="ui center aligned header">
							About / FAQ
						</h3>
					</a>
				</div>
			</div>
			<div class="ui divider"></div>
			<div class="row">
				<div class="column">
					<h2 class="ui centered header">News</h2>
					<div class="ui divided grid">
						<div class="two column row">
							<div class="column">
								<h3 class="ui header">DreamHost Sponsor</h2>
								<p>_______________------___________</p>
							</div>
							<div class="column">
								<h3 class="ui header">Project of the month</h2>
								<p>_______________------___________</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection