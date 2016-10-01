<?php
namespace Zendvn\File;

class Image{
	
	public function __construct($options = null){
	}
	
	public static function upload($file, $dir){
	    $fileName      = $file['name'];
	    if(file_exists($dir . $fileName)){
	        $fileInfo      = pathinfo($fileName);
	        $fileName      = sanitize_title($fileInfo['filename']) . '_' . time() . '.' . $fileInfo['extension'];
	    }
	    move_uploaded_file($file['tmp_name'], $dir . $fileName);
	    return $fileName;
	}
    public static function resize($file =  null, $dir, $width = 0, $height = 0, $prefix = ''){
		
		//Duong dan thu muc de luu hinh anh moi
		$storeFolder = $dir . DS . $prefix . $width . 'x' . $height;
		if(!file_exists($storeFolder)){
			mkdir($storeFolder,0755); //CMOD
		}
		
		$newImgPath   = $storeFolder . DS . $file['name'];
		$imgDir       = $dir . DS . $file['name'];
		if(!file_exists($newImgPath)){
			$wpImageEditor = wp_get_image_editor($imgDir);
			if(!is_wp_error($wpImageEditor)){
				$wpImageEditor->resize($width, $height, array('center','center'));
				$wpImageEditor->save($newImgPath);
			}
		}
	}
}