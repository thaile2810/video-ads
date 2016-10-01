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
$ordering = array(
                'required'      => false,
                'type'          => 'text',
                'validators'    => array(
                    'Digits' => array('name'    => 'Zend\Validator\Digits')
                )
            );
return array( 
        'default' => array(
                        'name' => $name,
                        'ordering' => $ordering,
                        )
);
