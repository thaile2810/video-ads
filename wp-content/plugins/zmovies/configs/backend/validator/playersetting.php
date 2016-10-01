<?php
$width = array(
    'required'      => true,
    'type'          => 'text',
    'validators'    => array(      
        'Digits'        => array('name'    => 'Zend\Validator\Digits')
    )
);

$height = array(
    'required'      => true,
    'type'          => 'text',
    'validators'    => array(
        'Digits'        => array('name'    => 'Zend\Validator\Digits')
    )
);





return array(
    'default' => array(
        'width'      => $width,  
        'height'     => $height                      
    )
);