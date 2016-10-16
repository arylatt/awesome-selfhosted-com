@extends('layouts.mgmt')

@section('content')
	<div class="ui container">
		<div class="ui grid">
			<div class="row">
				<div class="ten wide column">
					<h1 class="ui header">
						Collaborator Panel
						<div class="sub header">
							manage the shit out of that repo
						</div>
					</h1>
				</div>
				<div class="right aligned six wide column">
					<span class="ui massive {{ $last['color'] }} label">LAST SCAN: {{ $last['text'] }}</span>
				</div>
			</div>
			<div class="row">
				<table class="ui striped table">
					<thead>
						<tr>
							<th class="collapsing">ID</th>
							<th>Start</th>
							<th>End</th>
							<th>Creator</th>
							<th>Status</th>
							<th>Invalid Items</th>
						</tr>
					</thead>
					<tbody>
						@foreach($scans as $scan)
							<tr>
								<td>{{ $scan->scan_id }}</td>
								<td>{{ \Carbon\Carbon::parse($scan->scan_start)->format('d/m/Y H:i') }}</td>
								<td>{{ \Carbon\Carbon::parse($scan->scan_end)->format('d/m/Y H:i') }}</td>
								<td>{{ $scan->Creator->user_name }}</td>
								{!! $scan->FormatStatus() !!}
								<td>{{ count($scan->InvalidItems->all()) }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection