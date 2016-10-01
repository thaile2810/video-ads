<?php
$name = array(
    'required'      => true,
    'type'          => 'text',
    'validators'    => array(
        'NotEmpty'      => array('name'     => 'Zend\Validator\NotEmpty'),
    )
);



$ordering = array(
    'required'      => true,
    'type'          => 'text',
    'validators'    => array(
        'Digits' => array('name'    => 'Zend\Validator\Digits')
    )
);

return array(
    'default' => array(
        'name'      => $name,  
        'ordering'  => $ordering,                         
    )
);