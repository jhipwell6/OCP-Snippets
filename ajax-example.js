var ajaxurl = '../../wp-admin/admin-ajax.php',
	onDeck = [],
	inProgress = [],
	completed = [];
	
	$('#designs-on-deck').find('li').each(function(){
		onDeck.push($(this).text());
	});
	$('#designs-in-progress').find('li').each(function(){
		inProgress.push($(this).text());
	});
	$('#designs-completed').find('li').each(function(){
		completed.push($(this).text());
	});
	
$.post(
	ajaxurl, 
	{
		'action': 'update_designs', // must match ajax function name
		'on-deck': onDeck,
		'in-progress': inProgress,
		'completed': completed,
		'id': $('#project-wrapper').data('id')
	},
	function(response){
	}
);