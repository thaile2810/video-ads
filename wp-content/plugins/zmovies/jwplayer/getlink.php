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
    echo '<br/>' . $tmp;
    echo '<pre>';
    print_r(get_headers('https://r7---sn-i3b7kney.c.docs.google.com/videoplayback?requiressl=yes&id=d72a54c0de4f67cd&itag=43&source=webdrive&app=texmex&ip=113.162.189.206&ipbits=8&expire=1448062498&sparams=requiressl%2Cid%2Citag%2Csource%2Cip%2Cipbits%2Cexpire&signature=60C078297063929A750A934855CC16FD1426B3A0.46387FAB9F6892243617ACA1CB11E6C087C7C8DA&key=ck2&mm=30&mn=sn-i3b7kney&ms=nxu&mt=1448058815&mv=m&nh=IgpwcjAxLmhrZzA4KgkxMjcuMC4wLjE&pl=19'));
    echo '</pre>';
    
    
    
    //get_headers($tmp);
   /*  $content = file_get_contents($tmp);
    echo $content; */
   