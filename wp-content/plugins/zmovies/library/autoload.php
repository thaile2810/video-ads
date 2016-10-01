<?php
// load tập tin StandardAutoloader.php
require_once ZMOVIES_LIBRARY_PATH . DS . 'Zendvn' . DS . 'Loader' . DS . 'StandardAutoloader.php';

// gọi lớp StandardAutoloader
$loader = new \Zendvn\Loader\StandardAutoloader(array(
                                        'namespaces'                => array(
            			                     'Zend'                 => ZMOVIES_LIBRARY_PATH .'/Zend',
            			                     'Zendvn'               => ZMOVIES_LIBRARY_PATH .'/Zendvn',
//                                              'PHPImageWorkshop'	    => ZENDVN_LIBRARY_PATH . '/PHPImageWorkshop',
            	                        ),
//                                         'prefixes' => array(
//                                             'HTMLPurifier'          => ZENDVN_LIBRARY_PATH . '/HTMLPurifier',
//                                         )
                                ));
// đăng ký loader
$loader->register();