<?php
namespace Zmovies\Helper;

class FileUpload{
	
	public $_options = array();
	
	public function __construct($options = array()){
		$this->_options = $options;	
	}
	
	public static function upload($file, $dir){
	    	   
	    $fileName      = mb_strtolower($file['name']);
	    $fileInfo      = pathinfo($fileName);	   
	    
	    $newFileName = sanitize_title($fileInfo['filename']) . '.' . $fileInfo['extension'];
	    
	    if(file_exists($dir . $newFileName)){
	        $fileInfo      = pathinfo($newFileName);
	        $newFileName   = sanitize_title($fileInfo['filename']) . '_' . time() . '.' . $fileInfo['extension'];
	    }
	    move_uploaded_file($file['tmp_name'], $dir . $newFileName); 
	    return $newFileName;
	}
	
	public function resize($filePath =  null, $resizeDir = null, $width = null, $height = null, $prefix = ''){
		
		if(!file_exists($resizeDir)){
		    mkdir($resizeDir,0755); //CMOD
		}
		
		$fileInfo   = pathinfo($filePath);
				
		$newImgPath = $resizeDir . $fileInfo['basename'];
		if(!file_exists($newImgPath)){
		    $wpImageEditor = wp_get_image_editor($filePath);
		    if(!is_wp_error($wpImageEditor)){
		        $wpImageEditor->resize($width, $height, array('center','center'));
		        $wpImageEditor->save($newImgPath);
		    }
		}
		return $newImgPath;
		
	}
	
}