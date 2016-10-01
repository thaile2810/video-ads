<?php
namespace Zmovies\Helper;

class ShowAds{	
    
    public function __construct(){
        
    }
    
    public function show($area = null){
        
        if($area != null){
                
            if($area == 'homepage') $this->homePageAds();
            
            if($area == 'top_content') $this->topContentAds();
            
            if($area == 'left') $this->leftAds();
            
            if($area == 'right') $this->rightAds();
            
            if($area == 'bottom') $this->bottomAds();
            
            if($area == 'bottom_content') $this->bottomContentAds();
        }
    }
    
    public function getAds($area = null){
        global $wpdb;
         
        if($area != null){            
            $sql = "SELECT *
                    FROM " . ZMOVIES_TABLE_ADS . "
                            WHERE status = 'active'
                            AND area = '{$area}'
                            ORDER BY RAND() LIMIT 1";
            
            $result = $wpdb->get_row($sql, ARRAY_A);
            return $result;
            
        }
        return false; 
    }
    
    public function replaceContent($content = null){
        if($content != null){
            $content        = str_replace('\"', '"', $content);
            $content        = str_replace("\'", "'", $content);
            return $content;
        }
    }
    public function homePageAds(){

        $result = $this->getAds('homepage');
       
        $html = '';
        if($result != false){            
            $html ='<div class="container ads-home">
                        <div class="row">
                            <div class="col-md-12">' . $this->replaceContent($result['content']) . '</div>
                        </div>
                    </div>';
            echo $html;
         }
        
    }
    
    public function topContentAds(){

        $result = $this->getAds('top_content');
         
        $html = '';
        if($result != false){
            $html ='<div class="container ads-top-content">
                        <div class="row">
                            <div class="col-md-12">' . $this->replaceContent($result['content']) . '</div>
                        </div>
                    </div>';
            echo $html;
        }
    }
    
    public function leftAds(){

        $result = $this->getAds('left');
         
        $html = '';
        if($result != false){
            $html ='<div class="zWLeft">
                            <div class="col-md-12">' . $this->replaceContent($result['content']) . '
                    </div>
                    </div>';
            echo $html;
        }
    }    

    public function rightAds(){
        $result = $this->getAds('right');
         
        $html = '';
        if($result != false){
            $html ='<div class="zWgRight">
                        <div class="textwidget">
                        ' . $this->replaceContent($result['content']) . '
                        </div>
                    </div>';
            echo $html;
        }
        
    }

    public function bottomAds(){

        $result = $this->getAds('bottom');
         
        $html = '';
        if($result != false){
            $html ='<div class="container ads-bottom">
                        <div class="row">
                            <div class=" col-md-12">' . $this->replaceContent($result['content']) . '</div>
                        </div>
                    </div>';
            echo $html;
        }
    }

    public function bottomContentAds(){

        $result = $this->getAds('bottom_content');
         
        $html = '';
        if($result != false && $this->replaceContent($result['content'])!='' ){
            $html ='<div class="ads-center">
                        <div class="ads-bottom-content">
                            <div class="row">
                                <div class="col-md-12">' . $this->replaceContent($result['content']) . '</div>
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>';
            echo $html;
        }
    }
    
    
}