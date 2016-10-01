<!DOCTYPE html>
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>Chạy video với link youtube</title>
    <script type='text/javascript' src='jwplayer/jwplayer.js'></script>
    <script>jwplayer.key="ViNOqCRLyJo3TOuGe1B+nfrOMBmJy7qPIiAF7w==";</script>
    </head>
  <body>
    
    <h1>Chạy video với link youtube</h1>
    <div id="player"></div>
    <?php
     
         $url = 'https://drive.google.com/file/d/0B4InMYmRZBehaEhUa19WZmVpSkU/view?pli=1';
        
        $content = file_get_contents($url);
        //echo $content;
        
        $pattern = '#"601-thay-doi-footer.mp4","(.)*"#imU';
        preg_match_all($pattern, $content,$matches);
        $tmp =  $matches[0][0];
        
        $tmp = explode(',', $tmp);
        $tmp = $tmp[1];
        $tmp = str_replace('\u003d', '=', $tmp);
        $tmp = str_replace('"', '', $tmp);
        echo '<pre>';
        print_r($tmp);
        echo '</pre>';
    ?>
    <script type='text/javascript'>
    var playerInstance = jwplayer('player');
      playerInstance.setup({ 
        //file: 'https://www.youtube.com/watch?v=sDvXQWZAQYc'
        //file: 'https://googledrive.com/host/0B4InMYmRZBehaTk5S01CSFNHR0U/001-login-gioi-thieu.mp4' 
    	  file: '<?php echo $tmp;?>'
        	
        //image: 'images/background.jpg'
      });
    </script>
   
  </body>
</html>