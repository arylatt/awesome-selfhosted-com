$(document).ready(function() {
	$('.ui.dropdown').dropdown();
	$('#awesh_search').search({
		apiSettings: {
			url: 'search/{query}',
			action: 'search',
		},
		type: 'category',
		showNoResults: true,
		maxResults: 10,
		minCharacters: 3
	})
});