<?php
// =========================== name =============================
$name = array(
            'required'      => true,
            'type'          => 'text',
            'validators'    => array(
                'NotEmpty'      => array('name'     => 'Zend\Validator\NotEmpty'),
                /* 'NoRecordExits' => array(
                                        'name'		=> 'Zendvn\Validator\Db\NoRecordExits',
                                        'options'	=> array(
                                            'table'		=> ZENDVN_RES_TABLE_ROOM,
                                            'field'		=> 'name',
                                            'exclude'	=> array(
                                            					'field'	=> 'id',
                                            					'value'	=> 	ID	
                                            			         )
                                        ) 
                )*/
            )
        );
// =============================== min =============================
$min = array(
            'required'      => false,
            'type'          => 'text',
            'validators'    => array(
                'Digits' => array('name'    => 'Zend\Validator\Digits')
            )
        );
// =============================== max =============================
$max = array(
                'required'      => false,
                'type'          => 'text',
                'validators'    => array(
                    'Digits' => array('name'    => 'Zend\Validator\Digits')
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
                        'min' => $min,
                        'max' => $max,
                        'ordering' => $ordering,
                        )
);
