(function($){
	
	$("a[data-eps='s0-m0']").addClass('movie-view');
	
	var playerInstance = jwplayer('player');
	var imgUrl         = $('input[name=background-video]').val();
	
	playerInstance
			.setup({
				file : mFile,
				image : imgUrl,
				width : "640px",
				height: "480px", 
				type  : "mp4",
				
			});
	
	

})(jQuery);