<?php
namespace Zendvn\Taxonomy;

class BulkAction {
    public $_taxonomy;
    private $_actions = [];

    public function __construct($taxonomy = 'taxonomy') {
        $this->_taxonomy = $taxonomy;
    }

    public function register($args = '') {
        
        $action_name = @$args['action_name'];
        if ($action_name === '') {
            $action_name = lcfirst(str_replace(' ', '_', @$args['menu_text']));
        }
        $this->_actions[$action_name] = $args;
    }

    public function bulkFooter() {
        global $taxnow;
    	$jquery = '';
		if($taxnow == $this->_taxonomy) {
		    
		    $jquery .= '<script type="text/javascript">jQuery(document).ready(function($) {';
		    
		    foreach($this->_actions as $action_name => $action) {
                $jquery .= '$("select[name=\'action\']").append(\'<option value="' . $action_name . '">' . $action["menu_text"] . '</option>\');';
                $jquery .= '$("select[name=\'action2\']").append(\'<option value="' . $action_name . '">' . $action["menu_text"] . '</option>\');';
		    }
		    $jquery .= '});</script>';
		}
		echo $jquery;
	}
	public function getAction(){
	    return $this->_actions;
	}
	
}