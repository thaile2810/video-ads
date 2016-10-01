<?php
namespace Zmovies\Models;

use Zendvn\Inc\ZendvnHtml;
if(!class_exists('WP_List_Table')){
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Ads extends \WP_List_Table{
	private $_per_page = 20;	
	private $_sql;
	
	//1.1 Xay dung ham construct
	public function __construct(){	
		parent::__construct(array(
				'plural'    => 'id',
				'singular'  => 'id',
				'ajax'      => false,
				'screen'    => null,
		) );	
	}
	
	//1.2 Xay dung ham construct
	public function prepare_items(){
		$columns 	= $this->get_columns();
		$hidden 	= $this->get_hidden_columns();
		$sortable 	= $this->get_sortable_columns();
			
		$this->_column_headers 	= array($columns,$hidden,$sortable);
		$this->items 			= $this->table_data();
	
		$total_items 	= $this->total_items();
		$per_page 		= $this->_per_page;
		$total_pages 	= ceil($total_items/$per_page);
	
		$this->set_pagination_args(array(
				'total_items' 	=> $total_items,
				'per_page' 		=> $per_page,
				'total_pages' 	=> $total_pages
		));	
	}

	public function get_bulk_actions(){
		$actions = array(	
		        'delete' 	=> __('Delete'),
		        'active' 	=> __('Active'),
		        'inactive' 	=> __('InActive'),
		    
		);
		return $actions;
	}

	private function total_items(){
		global $wpdb;
		return $wpdb->query($this->_sql);
	}
	public function count_items(){
	    global $wpdb; 
	    $query = $wpdb->get_var('SELECT COUNT(id) FROM '.ZMOVIES_TABLE_ADS);
	    $wpdb->flush();
	    return $query;
	}
	private function table_data(){
	
		$data = array();	
		global $wpdb,$zController;	
		$orderby 	= ($zController->getParams('orderby') == '')? 'id' : $_GET['orderby'];
		$order		= ($zController->getParams('order') == '')? 'DESC' : $_GET['order'];	

		$sql = 'SELECT m.* FROM ' . ZMOVIES_TABLE_ADS . ' AS m ';	

	

		$whereArr = array();
			
		if($zController->getParams('s') != ''){
			$s = esc_sql($zController->getParams('s'));
			$whereArr[] = " (m.name LIKE '%$s%') ";
		}
		
		if($zController->getParams('filter_status') != ''){
		    $status = esc_sql($zController->getParams('filter_status'));
		    $whereArr[] = " (m.status = '$status') ";
		}
		
		if($zController->getParams('filter_area') != ''){
		    $area = esc_sql($zController->getParams('filter_area'));
		    $whereArr[] = " (m.area = '$area') ";
		}
	
		if(count($whereArr)>0){
			$sql .= " WHERE " . join(" AND ", $whereArr);				
		}
	
		$sql .= ' ORDER BY m.' . esc_sql($orderby) . ' ' . esc_sql($order);
	
		//echo '<br/>' . $sql;
	
		$this->_sql  = $sql;
	
		$paged 		= max(1,@$_REQUEST['paged']);
		$offset 	= ($paged - 1) * $this->_per_page;
	
		$sql .= ' LIMIT ' . $this->_per_page . ' OFFSET ' . $offset;
	
	
		$data = $wpdb->get_results($sql,ARRAY_A);

		return $data;
	}
	
	public function get_sortable_columns(){
		return array(
				'name' 		=> array('name',true),
				'status' 	=> array('status',true),
				'id'		=> array('id', true),
				
		);
	}
	
	/*
	 * Show column
	 */
	public function get_columns(){
		$arr = array(
				'cb'			=> '<input type="checkbox" />',
				'name' 			=> __('Name',ZMOVIES_DOMAIN_LANGUAGE),
				'shortcode'		=> __('Shortcode',ZMOVIES_DOMAIN_LANGUAGE),				
				'area' 		    => __('Area',ZMOVIES_DOMAIN_LANGUAGE),
		        'status'        => __('Status',ZMOVIES_DOMAIN_LANGUAGE),
				'id'			=> 'ID'
		);
		return $arr;
	}
	
	public function get_hidden_columns(){
		return array();
	}
	
	public function deleteItem($arrData = array(), $options = array()){
		global $wpdb;
	
		if(!is_array($arrData['id'])){			
			$where 	= array('id' => absint($arrData['id']));
			$wpdb->delete(ZMOVIES_TABLE_ADS, $where);
		}else{
			$arrData['id'] = array_map('absint', $arrData['id']);
			$ids           = join(',', $arrData['id']);
			$sql           = "DELETE FROM " . ZMOVIES_TABLE_ADS . " WHERE id IN ($ids)";
			$wpdb->query($sql);
		}
	}
	
	
	//array('status'=>1),array('type'=>'all')
	public function getItem($arrData = array(), $options = array()){
		
		global $wpdb;		
		if(isset($options['type']) && $options['type'] == 'all'){		
		}else{
			$id = absint($arrData['id']);			
			$sql = "SELECT * FROM " . ZMOVIES_TABLE_ADS . " WHERE id = " . absint($id);
			$result = $wpdb->get_row($sql, ARRAY_A);
		}
		return $result;
	}
	
	public function save_item($arrData = array(), $options = array()){		
		global $zController, $wpdb;			
 		$action   = $arrData['action'];								
		$data = array(
				'name' 				=> sanitize_text_field($arrData['name']),
				'content' 			=> $arrData['content'],
				'area' 			    => sanitize_text_field($arrData['area']),				
				'ordering' 			=> sanitize_text_field($arrData['ordering']),
		        'status' 			=> sanitize_text_field($arrData['status']),				
		);		
 		$format = array('%s','%s','%s','%d','%s');				
		if($action == 'add'){
		    $data['shortcode'] = '';
		    $wpdb->insert(ZMOVIES_TABLE_ADS, $data);   		
    		$insertID   = $wpdb->insert_id;
    		if($insertID > 0 ){
        		$shortcode         = '[zvideo_ads id="' . $insertID . '" title="' . sanitize_title($data['name']) . '"]';
        		$data         = array('shortcode'=>$shortcode);
        		$wpdb->update(ZMOVIES_TABLE_ADS,$data,array('id'=> absint($insertID)));
    		}
		}else if ($action == 'edit'){		 
 		    $shortcode         = '[zvideo_ads id="' . absint($arrData['id']) . '" title="' . sanitize_title($data['name']) . '"]';		    
 		    $data['shortcode'] = $shortcode;
 			$where = array('id'=> absint($arrData['id']));			
 			$wpdb->update(ZMOVIES_TABLE_ADS,$data,$where);
		}	 
	}
	

	public function column_default($item, $column_name){
	
		return $item[$column_name];
	}
	
	
	public function column_name($item){
	
		global $zController;	
		$page       = $zController->getParams('page');	
		$name       = 'security_code';	
		$lnkDelete 	=  add_query_arg(array('action'=>'delete','id'=>$item['id']));
		$action 	= 'delete_id_' . $item['id'];
		$lnkDelete 	= wp_nonce_url(str_replace('&msg=1','', $lnkDelete),$action,$name);		
		$actions = array(
				'edit' 		=> '<a href="?page=' . $page . '&action=edit&id=' . $item['id'] . '">Edit</a>',
				'delete' 	=> '<a href="' . $lnkDelete . '">Delete</a>'
		);	
		$html = '<strong><a href="?page=' . $page . '&action=edit&id=' . $item['id'] . '">' . $item['name'] .'</a></strong>'
		. $this->row_actions($actions);
		return $html;
	}
	
	
	public function column_cb($item){
		$singular = $this->_args['singular'];
		$html = '<input type="checkbox" name="' . $singular .'[]" value="' . $item['id'] .'" />';
		return $html;
	}
	
	public function column_status($item){
	    global $zController;
	    
	    $action    = 'inactive';
	    if($item['status'] == 'inactive'){	       
	        $src   = ZMOVIES_IMAGES_URL . '/icons/inactive.png';
	    }else{
	        $action    = 'active';
	        $src       = ZMOVIES_IMAGES_URL . '/icons/active.png';
	    }	        	        	   	    	
	    $paged      = max(1,@$_REQUEST['paged']);
	    $name 		= 'security_code';
	    $lnkStatus 	= add_query_arg(array('action'=>$action,'id'=>$item['id'],'paged'=> $paged));
	    $action 	= $action . '_id_' . $item['id'];
	    $lnkStatus 	= wp_nonce_url($lnkStatus,$action,$name);
	    $html = '<img alt="" src="' . $src . '">';
	    $html = '<a href="' . $lnkStatus . '">' . $html . '</a>';	    
	    return $html;	   
	}
	
	
	public function changeStatus($arrData = array(), $options = array()){
	    global $wpdb;
	   
 	    $tableName = ZMOVIES_TABLE_ADS;	    	    
	    if(!is_array($arrData['id'])){
	        $status = ($arrData['action'] == 'active')? 'inactive': 'active';
	        $data 	= array('status' => $status);
	        $where 	= array('id' => absint($arrData['id']));
	        $wpdb->update($tableName, $data, $where);
	    }else{	       
 	        $status = $arrData['action'];
 	        $arrData['id'] = array_map('absint', $arrData['id']);
 	        $ids = join(',', $arrData['id']);
 	        $sql = "UPDATE $tableName SET status = '$status' WHERE id IN ($ids)";
 	        $wpdb->query($sql);
	    }
	    
	}
	
	//==============Filter======================
	protected function extra_tablenav($which){
	    global $zController;
	    
	    if($which == 'top'){
	        $htmlObj = new ZendvnHtml();
	       
	
	        $filterVal = @$_REQUEST['filter_status'];
	        $options['data'] = array(
	            '0'            => __('Status filter',ZMOVIES_DOMAIN_LANGUAGE),
	            'active'       => __('Active',ZMOVIES_DOMAIN_LANGUAGE),
	            'inactive'     => __('Inactive',ZMOVIES_DOMAIN_LANGUAGE)
	        );	
	        $slbFilter 	= $htmlObj->selectbox('filter_status',$filterVal,array(),$options);	

	        $AreaHelper            = $zController->getHelper('Area');	
	        $optionsAre['data']['0']  = __('Area filter',ZMOVIES_DOMAIN_LANGUAGE);
	        $are                   = $AreaHelper->getArea();
	        foreach ($are as $key => $val){
	            $optionsAre['data'][$key] = $val;
	        }
	        $filterVal = @$_REQUEST['filter_area'];
	        
	        
	        $areaFilter = $htmlObj->selectbox('filter_area',$filterVal,array(),$optionsAre);
	        $attr 		= array('id'=>'filter_action','class'=>'button');
	        $btnFilter 	= $htmlObj->button('filter_action',__('Filter',ZMOVIES_DOMAIN_LANGUAGE),$attr);	
	        echo '<div class="alignleft actions bulkactions">'
	            . $slbFilter . $areaFilter  . ' '   . ' ' . $btnFilter
	            .'</div>';
	    }
	    
	    
	}
	
	
	
	
}