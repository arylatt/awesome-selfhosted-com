@extends('layouts.mgmt')

@section('content')
	<div class="ui container">
		<div class="ui grid">
			<div class="row">
				<div class="ten wide column">
					<h1 class="ui header">
						Profile
						<div class="sub header">
							Edit your collaborator profile.
						</div>
					</h1>
				</div>
				<div class="ui form">
					<div class="two fields">
						<div class="field">
							<label for="name">Your Username</label>
							<input type="text" id="name" readonly="" placeholder="{{ Auth::user()->user_name }}"/>
						</div>
						<div class="field">
							<label>Your Description</label>
    						<textarea rows="2" maxlength="50" style="margin-top: 0px; margin-bottom: 0px; height: 124px;" placeholder="{!! $collab->StaffBio() !!}"></textarea>
						</div>
					</div>
					<div class="right aligned field">
						<div class="ui green button">
							<i class="send icon"></i>
							Update Profile
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection