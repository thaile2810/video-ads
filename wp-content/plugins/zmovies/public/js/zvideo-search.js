jQuery(document).ready(function ($){
	
	$(".z-search-main").click(function(){
		var s = $('input[name="s"]').val();
		if(s != ''){
		window.location = $(this).data('href') + '/' + s.replace(/\s/g, "+");
		}
	});
	$('input[name="s"]').keypress(function (e) {
		var key = e.which;
		if(key == 13){
			$(".z-search-main").click();
		    return false;  
		}
	});
	//console.log('zvideo',zvideo);
    $('input[name="s"]').autocomplete({
        source: function(req, response){
            $.getJSON(zvideo.ajaxurl + '?callback=?&action=process&func=search', req, response);
        },
        select: function(event, ui) {
            window.location.href=ui.item.link;
        },
        minLength: 3,
        messages: {
            noResults: '',
            results: function() {}
        }
    });   
});