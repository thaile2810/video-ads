//console.log('admin-tax.js');
jQuery(document).ready(function($) {
	$action_temp = $('input[name="action"').val();
	var status_temp = '';
	$("#submit").unbind("click");
	$('#submit').click(function(){
		
		$taxonomy = jQuery.trim(jQuery('input[name="taxonomy"').val());
		$action   = jQuery.trim(jQuery('input[name="action"').val());
		$tagID    = jQuery.trim(jQuery('input[name="tag_ID"').val());
		
		$allowTax = ['zvideos_playlist','zvideos_youtube_user','zvideos_channel'];
		if($action == 'add-tag'){
			if($allowTax.indexOf($taxonomy) != -1){
				$url = jQuery.trim(jQuery('input[name="' + $taxonomy + '[url]"').val());
				var form = $('#submit').parents('form');
				if($url == 0 && $url != " "){
					jQuery('.spinner').css("visibility", "hidden");
					jQuery('#title').focus();
					if (!validateForm(form))
						return false;
				}else{
					
					//console.log('$url',$url);
					// Note : chú ý 
					if($action == 'editedtag'){
						$data = $('#edittag').serializeArray();
					}else{
						$data = $('#addtag').serializeArray();
				    }
					$options = {
					            'action': 'process', 
					            'func':'is-tax',
				            	'data': $data
								};
					
					$.ajax({
						url			: zvideo.ajaxurl,
						type		: "POST",
						data		: $options,
						dataType	: "text",
						success		: function(data, status, jsXHR){
	
										var obj = jQuery.parseJSON(data);
										$('.z-error').html('');
										
										if(obj.status == false){
											$('.z-error').html(obj.error.zvideos_youtube_user);
											$('.z-error').html(obj.error.zvideos_channel);
											$('.z-error').html(obj.error.zvideos_playlist);
	
											if (!validateForm(form))
												return false;
										}else{
											console.log('$action',$action);
											if($action == 'add-tag' || $action == 'editedtag'){
												customTaxClick(form);
											}										
											
										}
									}
					})
					return false;
				}
			}
		}
	});
	
	function customTaxClick(form){

		if(!validateForm(form))
			return false;

		$.post(ajaxurl, $('#addtag').serialize(), function(r){
			var res, parent, term, indent, i;

			$('#ajax-response').empty();
			res = wpAjax.parseAjaxResponse( r, 'ajax-response' );
			if ( ! res || res.errors )
				return;

			parent = form.find( 'select#parent' ).val();

			if ( parent > 0 && $('#tag-' + parent ).length > 0 ) 
				$( '.tags #tag-' + parent ).after( res.responses[0].supplemental.noparents );
			else
				$( '.tags' ).prepend( res.responses[0].supplemental.parents ); 

			$('.tags .no-items').remove();

			if ( form.find('select#parent') ) {
				term = res.responses[1].supplemental;

				indent = '';
				for ( i = 0; i < res.responses[1].position; i++ )
					indent += '&nbsp;&nbsp;&nbsp;';

				form.find( 'select#parent option:selected' ).after( '<option value="' + term.term_id + '">' + indent + term.name + '</option>' );
			}

			$('input[type="text"]:visible, textarea:visible', form).val('');
		});
	}
});