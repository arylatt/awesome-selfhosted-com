<div class="ui top fixed menu">
	<div class="ui container">
		<a href="/" class="header item">
			Awesome-Selfhosted
		</a>
		<div class="right menu">
			@if(Auth::check())
				<div class="ui dropdown item">
					{{ Auth::user()->user_name }}
					<i class="dropdown icon"></i>
					<div class="menu">
						@if(Auth::user()->IsAdmin())
							<a href="/admin" class="item">
								<i class="settings icon"></i>
								Admin Panel
							</a>
						@endif
						@if(Auth::user()->IsCollab())
							<a href="/mgmt" class="item">
								<i class="configure icon"></i>
								Collaborator Panel
							</a>
							<div class="ui divider"></div>
						@endif
						<a href="/logout" class="item">
							<i class="sign out icon"></i>
							Sign Out
						</a>
					</div>
				</div>
			@else
				<a href="/login" class="item">
					<i class="github icon"></i>
					Sign In
				</a>
			@endif
		</div>
	</div>
</div>