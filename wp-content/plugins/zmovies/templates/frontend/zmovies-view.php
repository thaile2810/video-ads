<div id="primary" class="content-area col-sm-12 col-md-8">
	<main id="main" class="site-main" role="main">	
	<div id="zmovie-view">

		<div class="row">
			<div class="col-sm-12 col-md-12 player">
			     <div id="player"></div>
			     <script type='text/javascript'>
    			      var playerInstance = jwplayer('player');
                      playerInstance.setup({ 
                        file    : 'http://www.zend.vn/download/anime/Anime-demo-001.mp4', 
                        image   : 'http://localhost/wp-test/wp-content/plugins/zmovies/public/images/anime-movies2.jpg',
                        url     : "http://localhost/wp-test/wp-content/plugins/zmovies/public/css/my-player.css",
                        width   : "100%",
                        aspectratio: "16:9"
                      });
                </script>
			</div>
		</div>
		<div class="row movie-control hidden-xs hidden-sm">
		      <a class="btn btn-primary btn-xs next-movie" role="button">Next movie</a>
		      <a class="btn btn-primary btn-xs" role="button">Movie die</a>
		      <a class="btn btn-primary btn-xs move-player" role="button">Zoom out</a>
		      <a class="btn btn-primary btn-xs light-off" role="button">Light Off</a>
		      <a class="btn btn-primary btn-xs" role="button">Download</a>
		</div>
		<div class="row movie-servers">
		  <ul>
		      <li>
		          <i class="fa fa-server"></i> <span>Youtube:</span> <a href="#">01</a> <a href="#">02</a> <a href="#">03</a>
		      </li>
		      <li>
		          <i class="fa fa-database"></i> <span>Youtube:</span> <a href="#">01</a> <a href="#">02</a> <a href="#">03</a>
		      </li>
		      <li>
		          <i class="fa fa-database"></i> <span>Youtube:</span> <a href="#">01</a> <a href="#">02</a> <a href="#">03</a>
		      </li>
		  </ul>
		</div>
		<script type='text/javascript'>
		(function($){
			var zoomFlag = false;
			$('.move-player').on('click',function(){
			    if(zoomFlag == false){
			    	 $("#player").animate({width:"950px"})
			    	             .css('position','absolute');
			    	 
			    	 $('.movie-control').animate({"margin-top":"530px"});
			         $('#secondary').animate({"margin-top":"550px"});	 
			         
			         $('.move-player').text('Zoom in');
			         zoomFlag = true;			
			    }else{
			    	 $("#player").animate({"width":"100%"}).css('position','relative').css('z-index','');
			    	 
			         $('#secondary').animate({"margin-top":"0px"});	 
			         $('.movie-control').animate({"margin-top":"0px"});
			         
			         $('.move-player').text('Zoom out');
			         zoomFlag = false;
			    }
			});
			
			$('.light-off').on('click',function(){
				$("#light-off").addClass('light-off-active').css("display","block");	
				$("#player").css({'position':'absolute','z-index':'131'});			
			});

			$(".light-off-inactive").on("click",function(){
			    $('#light-off').removeClass('light-off-active').css("display","none");
			    if(zoomFlag == false){
			    	$("#player").css('position','').css('z-index','');
			    }
			});
			
		})(jQuery);
		</script>
	</div>

	<div class="row">
		<div class="col-sm-12 col-md-12 zmovies">
			<div class="thumbnail">
				<img
					src="<?php echo  ZMOVIES_IMAGES_URL .'/ads.gif';?>"
					alt="...">
			</div>
		</div>
	</div>
	</main>
	<!-- #main -->
</div>
<!-- #primary -->





