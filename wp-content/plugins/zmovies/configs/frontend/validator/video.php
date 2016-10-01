<?php
$title = array(
    'required'      => true,
    'type'          => 'text',
    'validators'    => array(
        'NotEmpty'      => array('name'     => 'Zend\Validator\NotEmpty'),     
    )
);



$url = array(
    'required'      => true,
    'type'          => 'text',
    'validators'    => array(
         'NotEmpty'      => array('name'     => 'Zend\Validator\NotEmpty'),
    )
);

return array(
    'default' => array(
        'post_title'      => $title,  
        'post_url'        => $url,                         
    )
);