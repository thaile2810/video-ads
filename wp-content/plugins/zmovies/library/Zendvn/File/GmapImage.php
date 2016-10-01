<?php
namespace Zendvn\File;

class GmapImage{
	
private $_zoom      = 21;
    private $_bounds    = array();
//     private $_imgs      = array();
    private $_edge      = 256;
    private $_dir;
    private $_ext;
    
    public function __construct($source, $dir){
        $this->_dir = $dir;
        $this->_setExt($source);
        $this->_createdDir($dir);
        $this->_cloneImg($source);
    }
    private function _setExt($image){
        $image = basename($image);
        preg_match("'^(.*)\.(gif|jpe?g|png)$'i", $image, $ext);
        $this->_ext = strtolower($ext[2]);
    }
    
    private function _createdDir($dir){
        if(!file_exists($dir)){
            mkdir($dir,0755); //CMOD
        }
    }
    private function _createMaxImage($source,$zoom){
        switch ($this->_ext) {
            case 'jpg' :
            case 'jpeg': $source   = imagecreatefromjpeg($source);
            break;
            case 'gif' : $source   = imagecreatefromgif($source);
            break;
            case 'png' : $source   = imagecreatefrompng($source);
            break;
            default    : 
            break;
        }
        
        $source_width   = imagesx($source);
        $source_height  = imagesy($source);
        
        $col            = ceil($source_width / $this->_edge);
        $row            = ceil($source_height / $this->_edge);
        
        $new_width      = $col%2 == 0 ? $this->_edge * $col : $this->_edge * ($col + 1);
        $new_height     = $row%2 == 0 ? $this->_edge * $row : $this->_edge * ($row + 1);
        
        $x              = ($new_width/2) - ($source_width/2);
        $y              = ($new_height/2) - ($source_height/2);
        
        $background = $this->_createBackround($new_width,$new_height);
        imagecopyresized ($background, $source, $x, $y, 0, 0, $source_width, $source_height, $source_width, $source_height);
//         imagejpeg( $background, $this->_dir . DS . $zoom . ".jpg");
        
        $bounds   = $this->_createBound($zoom, $col, $row);
        if($bounds){
        
            $this->_bounds[$zoom]   = $bounds;
            $this->_cropImage($background,$zoom);
        
            return array(
                        'source'    => $background,
                        'width'     => $new_width,
                        'height'    => $new_height,
                        'col'       => $col - 1, 
                        'row'       => $row - 1
                        );
        }
        return false;
    }
    public function getBounds(){
        return $this->_bounds;
    }
    private function _createImage($mapImage,$zoom){
        
        $source_width   = $mapImage['width'];
        $source_height  = $mapImage['height'];
        $source         = $mapImage['source'];
        
        $col            = $mapImage['col'];
        $row            = $mapImage['row'];
        
        $width          = $col%2 == 0 ? $this->_edge * $col : $this->_edge * ($col + 1);
        $height         = $row%2 == 0 ? $this->_edge * $row : $this->_edge * ($row + 1);
        
        $background     = $this->_createBackround($width,$height);
        $percent        = $width > $height ? $height/$source_height : $width/$source_width;
        
        $new_width      = $source_width * $percent;
        $new_height     = $source_height * $percent;
        $x              = ($width/2) - ($new_width/2);
        $y              = ($height/2) - ($new_height/2);
        
        imagecopyresized( $background, $source, $x, $y, 0, 0, $new_width, $new_height, $source_width, $source_height);
//         imagejpeg( $background, $this->_dir . DS . $zoom . ".jpg");
        
        $bounds   = $this->_createBound($zoom, $col, $row);
        if($bounds){
        
            $this->_bounds[$zoom]   = $bounds;
            $this->_cropImage($background,$zoom);
            return array(
                'source'    => $background,
                'width'     => $width,
                'height'    => $height,
                'col'       => $col - 1,
                'row'       => $row - 1
            );
        }
        return false;
    }
    private function _cloneImg($source){
        $mapImage = array();
        for($zoom = 21; $zoom > 0; $zoom--){
            if($zoom == 21){
                $mapImage = $this->_createMaxImage($source,$zoom);
            }else{
                $mapImage = $this->_createImage($mapImage,$zoom);
            }
            if($mapImage == false) break;
        }
    }
    private function _createBound($zoom,$col,$row){
        
        $xmod = $col%2 == 0 ? $col/2 : ($col + 1)/2;
        $ymod = $row%2 == 0 ? $row/2 : ($row + 1)/2;
        
        $x = pow(2, $zoom - 1) - $xmod;
        $y = pow(2, $zoom - 1) - $ymod;
        
        $x2 = $col%2 == 0 ? $x + $col - 1 : $x + $col;
        $y2 = $row%2 == 0 ? $y + $row - 1 : $y + $row;
        
        if($x2 == $x - 1 || $y - 1 == $y2){
            return false;
        }
        return array(
                    array($x, $x2),
                    array($y, $y2),
                    );
    }
    
    private function _createBackround($width, $height) {
        $background = imagecreatetruecolor ( $width, $height );
        imagesavealpha($background, true );
        imagealphablending($background, false );
        $white = imagecolorallocatealpha ( $background, 179, 209, 255, 127);
        imagefill($background, 0, 0, $white );
        imagealphablending($background, true );
        return $background;
    }
    
    private function _cropImage($background,$zoom){
        $x1 = $this->_bounds[$zoom][0][0];
        $x2 = $this->_bounds[$zoom][0][1];
        $y1 = $this->_bounds[$zoom][1][0];
        $y2 = $this->_bounds[$zoom][1][1];
        $i1 = 0;
         
        for($i = $x1; $i <= $x2; $i++) {
            $j1 = 0;
            for($j = $y1; $j <= $y2; $j++) {
                $imgName = $zoom . "_" . $i . "_" . $j . "." . $this->_ext;
                //$this->_imgs[$zoom][$i1][$j1] = $imgName;
                $this->_crop($background,$imgName,$i1,$j1);
                $j1++;
            }
            $i1++;
        }
    
        //return $this->_imgs;
    }
    
    private function _crop($background,$name, $col, $row){
        $newImage   = @imagecreatetruecolor( $this->_edge, $this->_edge );
        imagealphablending($newImage, false);
        $color      = imagecolorallocate($newImage, 179, 209, 255);
        imagefill($newImage, 0, 0, $color);
    
        imagecopyresized( $newImage, $background, 0, 0, $col * $this->_edge, $row * $this->_edge, $this->_edge, $this->_edge, $this->_edge, $this->_edge );
        switch ($this->_ext) {
            case 'jpg' :
            case 'jpeg': imagejpeg($newImage,$this->_dir . DS . $name);
            break;
            case 'gif' : imagegif($newImage,$this->_dir . DS . $name);
            break;
            case 'png' : imagepng($newImage,$this->_dir . DS . $name);
            break;
            default    :
                break;
        }
        imagedestroy( $newImage );
    }
}