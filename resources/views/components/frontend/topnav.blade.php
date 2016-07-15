<div class="ui top fixed menu">
	<div class="ui container">
		<a href="/" class="header item">
			Awesome-Selfhosted
		</a>
		<div class="ui dropdown item">
			View List
			<i class="dropdown icon"></i>
			<div class="menu">
				<a href="/view/markdown" class="item">
					Markdown
				</a>
				<a href="/view/yaml" class="item">
					YAML
				</a>
				<a href="/view/json" class="item">
					JSON
				</a>
				<a href="/view/xml" class="item">
					XML
				</a>
			</div>
		</div>
		<a href="/submit" class="item">
			Submit Item
		</a>
		<a href="/team" class="item">
			The Team
		</a>
		<a href="https://chat.awesome-selfhosted.com/" class="item">
			Chat
		</a>
		<a href="https://gogs.awesome-selfhosted.com/" class="item">
			Gogs
		</a>
		<div class="right menu">
			<div id="awesh_search" class="ui category search item">
				<div class="ui transparent icon input">
					<input class="prompt" placeholder="Find Something..." type="text" />
					<i class="search link icon"></i>
				</div>
				<div class="results"></div>
			</div>
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