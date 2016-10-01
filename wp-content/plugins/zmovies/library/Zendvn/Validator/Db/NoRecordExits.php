<?php
namespace Zendvn\Validator\Db;

use Zend\Validator\AbstractValidator;

class NoRecordExits extends AbstractValidator{
    
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
        self::ERROR_RECORD_FOUND    => "A record matching the input was found",
        self::IS_EMPTY              => "Value is required and can't be empty",
    );

    public function __construct($options) {
        $this->table    = $options['table'];
        $this->field    = $options['field'];
        $this->exclude  = @$options['exclude'];
        
        parent::__construct($options);
    }

    public function isValid($value) {

        $this->setValue($value);
        $value = esc_sql(trim($value));
        
        if(!empty($value)){
            $result = $this->query($value);
            if ($result) {
                $this->error(self::ERROR_RECORD_FOUND);
                return false;
            }
        }else{
            $this->error(self::IS_EMPTY);
            return false;
        }

        return true;
    }
    
    private function query($value){
        global $wpdb;
        $sql = "SELECT id FROM " . $this->table . " WHERE " . $this->field . " = '" . $value . "'";
        if($this->exclude){
            $sql .= ' AND ' . $this->exclude['field'] . " != '" . $this->exclude['value'] . "'";
        }
        return $wpdb->get_row($sql,ARRAY_A);       
    }

}