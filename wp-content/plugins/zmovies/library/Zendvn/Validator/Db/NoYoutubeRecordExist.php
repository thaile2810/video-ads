<?php
namespace Zendvn\Validator\Db;

use Zend\Validator\AbstractValidator;

class NoYoutubeRecordExist extends AbstractValidator{
    
    /**
     * @var string
     */
    protected $table = '';
    
    /**
     * @var string
     */
    protected $field = '';
    
    /**
     * @var mixed
     */
    protected $exclude = null;
    
    const ERROR_NO_RECORD_FOUND = 'noRecordFound';
    const ERROR_RECORD_FOUND    = 'recordFound';
    const IS_EMPTY              = 'isEmpty';

    protected $messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => "No record matching the input was found",
        self::ERROR_RECORD_FOUND    => "Đường dẫn đã tồn tại trong hệ thống",
        self::IS_EMPTY              => "Value is required and can't be empty",
    );

    public function __construct($options) {
        parent::__construct($options);
    }

    public function isValid($value) {
        global $zController;
        $zController->getModel();
        //$this->setValue($value);
        
        
//         if(!empty($value)){
//             $result = $this->query($value);
//             if ($result) {
//                 $this->error(self::ERROR_RECORD_FOUND);
//                 return false;
//             }
//         }else{
//             $this->error(self::IS_EMPTY);
//             return false;
//         }

       // return true;
    }
    
    

}