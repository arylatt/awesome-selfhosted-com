<div class="ui top fixed inverted menu">
	<div class="ui container">
		<a href="/mgmt" class="header item">
			Collab Panel
		</a>
		<a href="/mgmt/run" class="item">
			<i class="play icon"></i>
			On-Demand Scan
		</a>
		<a href="/mgmt/results" class="item">
			<i class="book icon"></i>
			Scan Results
		</a>
		<a href="/mgmt/exceptions" class="item">
			<i class="flag icon"></i>
			Exceptions
		</a>
		<div class="right menu">
			<div class="ui dropdown item">
				{{ Auth::user()->user_name }}
				<i class="dropdown icon"></i>
				<div class="menu">
					<a href="/" class="item">
						<i class="home icon"></i>
						Main Site
					</a>
					@if(Auth::user()->IsAdmin())
						<a href="/admin" class="item">
							<i class="settings icon"></i>
							Admin Panel
						</a>
					@endif
					<a href="/logout" class="item">
						<i class="sign out icon"></i>
						Sign Out
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
