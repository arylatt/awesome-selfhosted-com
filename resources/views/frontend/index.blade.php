@extends('layouts.frontend')

@section('content')
	<div class="ui container">
		<div class="ui grid">
			<div class="row">
				<div class="column">
					<h1 class="ui header">
						Awesome-Selfhosted
					</h1>
					<p>goals we created a curated list of ___ ____ ___</p>
				</div>
			</div>
			<div class="ui divider"></div>
			<div class="five column centered row">
				<div class="column">
					<a href="/view/markdown">
						<i class="massive browser icon black"></i>
						<h3 class="ui center aligned header">
							View Our List
						</h3>
					</a>
				</div>
				<div class="column">
					<a href="/submit">
						<i class="massive edit icon black"></i>
						<h3 class="ui center aligned header">
							Contribute
						</h3>
					</a>
				</div>
				<div class="column">
					<a href="/sponsors">
						<i class="massive users icon black"></i>
						<h3 class="ui center aligned header">
							Our Sponsors
						</h3>
					</a>
				</div>
				<div class="column">
					<a href="/about">
						<i class="massive help icon black"></i>
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