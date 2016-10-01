<!DOCTYPE html>
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>Chạy video với link trực tiếp</title>
    <script type='text/javascript' src='jwplayer/jwplayer.js'></script>
    <script>jwplayer.key="ViNOqCRLyJo3TOuGe1B+nfrOMBmJy7qPIiAF7w==";</script>
    </head>
  <body>
    
    <h1>Chạy video với link trực tiếp</h1>
    <div id="player"></div>
    <script type='text/javascript'>
    var playerInstance = jwplayer('player');
      playerInstance.setup({ 
        file: 'video/C4lp6Dtd-640.mp4', 
        image: 'images/background.jpg'
      });
    </script>
   
  </body>
</html>