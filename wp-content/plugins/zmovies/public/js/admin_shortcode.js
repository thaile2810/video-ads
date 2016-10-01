jQuery(document).ready(function($) {
	var page_title = $('#zvideo-page_title').val();

	if(page_title == 'member_video'){
		var category = $('ul.zmovies-checklist li');
		category.each(function(k, val) {
			$($(this).children().children()).attr({
				'disabled': 'disabled'
			});
		});
	}

	$('#zvideo-page_title').change(function(event) {
		var me = $(this).val();
		if(me == 'member_video'){
			var category = $('ul.zmovies-checklist li');
			category.each(function(k, val) {
			$($(this).children().children()).attr({
				'disabled': 'disabled'
			});
			$($(this).children().children()).removeAttr('checked');
		});
		}else{
			var category = $('ul.zmovies-checklist li');
			category.each(function(k, val) {
			$($(this).children().children()).removeAttr('disabled');
		});
		}
	});
});