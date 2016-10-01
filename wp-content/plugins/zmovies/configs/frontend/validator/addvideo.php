<?php
$title = array(
    'required'      => true,
    'type'          => 'text',
    'validators'    => array(
        'NotEmpty'      => array('name'     => 'Zend\Validator\NotEmpty'),
        'NoRecordExits' => array(
            'name'		=> 'Zendvn\Validator\Db\NoYoutubeRecordExist',
            'options'	=> array(
                'table'		=> 'abc',
                'field'		=> 'post_url',              
            )
        )
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
        'title'      => $title,  
        'url'        => $url,                         
    )
);