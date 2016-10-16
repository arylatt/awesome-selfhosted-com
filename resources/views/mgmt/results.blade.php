@extends('layouts.mgmt')

@section('content')
	<div class="ui container">
		<div class="ui grid">
			<div class="row">
				<div class="ten wide column">
					<h1 class="ui header">
						Scan Results
						<div class="sub header">
							more than 0 errors is a fail
						</div>
					</h1>
				</div>
			</div>
			<div class="row">
				<div class="ui fluid accordion">
					<div class="active title">
						<i class="dropdown icon"></i>
						Invalid Items
					</div>
					<div class="active content">
						<table class="ui striped table">
							<thead>
								<tr>
									<th class="collapsing">ID</th>
									<th>Item</th>
									<th>Error</th>
								</tr>
							</thead>
							<tbody>
								@foreach($scan->InvalidItems as $item)
									<tr>
										<td>{{ $item->invalid_item_id }}</td>
										<td>{{ $item->invalid_item_text }}</td>
										<td>{{ $item->invalid_item_error }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="title">
						<i class="dropdown icon"></i>
						Log Entries
					</div>
					<div class="content">
						<table class="ui striped table">
							<thead>
								<tr>
									<th class="collapsing">ID</th>
									<th>Log</th>
									<th>Timestamp</th>
								</tr>
							</thead>
							<tbody>
								@foreach($scan->Log as $log)
									<tr>
										<td>{{ $log->scan_log_id }}</td>
										<td>{{ $log->scan_log_text }}</td>
										<td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection