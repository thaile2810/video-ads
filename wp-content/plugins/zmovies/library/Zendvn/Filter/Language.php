<?php
namespace Zendvn\Filter;

use Zend\Filter\FilterInterface;
use Stichoza\GoogleTranslate\TranslateClient;

class Language implements FilterInterface {
	
	protected $options;
	
	public function __construct($options = null){
	    $this->options = $options;
	}
	
	
	public function filter($value){
	    echo '<pre>';
	         print_r($value);
	    echo '</pre>';
	    require ZMOVIES_VENDOR_PATH . DS . 'autoload.php';
	    $tr = new TranslateClient();
	    $text = $tr->translate($value);
	    echo $tr->getLastDetectedSource();
	}
}