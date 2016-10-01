<?php
// =========================== name =============================
$name = array(
            'required'      => true,
            'type'          => 'text',
            'validators'    => array(
                'NotEmpty'      => array('name'     => 'Zend\Validator\NotEmpty'),               
            )
        );

// =============================== ordering =============================

return array( 
        'default' => array(
                        'name' => $name,
                        )
);
