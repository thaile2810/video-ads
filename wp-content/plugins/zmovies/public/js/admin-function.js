
jQuery(document).ready(function($){
	// function for meta 
	$('.zplaceholder').on('paste blur focus keydown keypress keyup', function(){
        if($(this).val() == ''){
        	$(this).prev('label').removeClass("screen-reader-text");
        }else{
        	$(this).prev('label').addClass("screen-reader-text");
        }
    });
	
	$('.z-tax-status').on('click', function(){
		$options = {
	            'action' 	: 'process', 
	            'func' 		: 'tax-change-status',
            	'data' 		: $(this).data('meta')
				};
		var sObj = $(this);
		$.ajax({
			url			: zvideo.ajaxurl,
			type		: "POST",
			data		: $options,
			dataType	: "text",
			success		: function(data, status, jsXHR){
							var obj = jQuery.parseJSON(data);
							if(obj.status == 'success'){
								sObj.children('img').attr('src',obj.src);
								sObj.data('meta',obj.meta);
							}
							console.log('obj',obj);
						}
		})
		return false;
	});
});
