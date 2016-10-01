<?php
namespace Zmovies\Models;

if(!class_exists('WP_List_Table')){
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class ShortCode extends \WP_List_Table{

	private $_per_page = 20;
	
	private $_sql;
	
	//1.1 Xay dung ham construct
	public function __construct(){
	
		parent::__construct(array(
				'plural' => 'id',
				'singular' => 'id',
				'ajax' => false,
				'screen' => null,
		) );
	
	}
	
	//1.2 Xay dung ham construct
	public function prepare_items(){
		//echo '<br/>' . __METHOD__;
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
		        'delete' 	=> 'Delete',
		);
		return $actions;
	}

	private function total_items(){
		global $wpdb;
		return $wpdb->query($this->_sql);
	}
	
	private function table_data(){
	
		$data = array();
	
		global $wpdb,$zController;
	
		//&orderby=title&order=asc
		$orderby 	= ($zController->getParams('orderby') == '')? 'id' : $_GET['orderby'];
		$order		= ($zController->getParams('order') == '')? 'DESC' : $_GET['order'];
	
		$sql = 'SELECT m.* FROM ' . ZMOVIES_TABLE_SHORTCODE . ' AS m ';
	
		$whereArr = array();
	
		if($zController->getParams('s') != ''){
			$s = esc_sql($zController->getParams('s'));
			$whereArr[] = " (m.name LIKE '%$s%') ";
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
	
		//echo '<br/>' . $sql;
	
		$data = $wpdb->get_results($sql,ARRAY_A);
	
		return $data;
	}
	
	public function get_sortable_columns(){
		return array(
				'name' 		=> array('name',true),
				'id'		=> array('id', true),
				
		);
	}
	
	/*
	 * Show column
	 */
	public function get_columns(){
		$arr = array(
				'cb'			=> '<input type="checkbox" />',
				'name' 			=> 'Name',
				'code'		    => 'Shortcode',				
				'created' 		=> 'Date',
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
			$wpdb->delete(ZMOVIES_TABLE_SHORTCODE, $where);
		}else{
			$arrData['id'] = array_map('absint', $arrData['id']);
			$ids = join(',', $arrData['id']);
			$sql = "DELETE FROM " . ZMOVIES_TABLE_SHORTCODE . " WHERE id IN ($ids)";
			$wpdb->query($sql);
		}
	}
	
	
	//array('status'=>1),array('type'=>'all')
	public function getItem($arrData = array(), $options = array()){
		
		global $wpdb;
		
		if(isset($options['type']) && $options['type'] == 'all'){
			$status = isset($arrData['status'])?absint($arrData['status']):'all';
			
			$sql = "SELECT * FROM " . ZMOVIES_TABLE_SHORTCODE;
			if($status != 'all'){
				$sql .= " WHERE status = $status ORDER BY ordering ASC ";
			}
			
			$result = $wpdb->get_results($sql,ARRAY_A);
			
		}else{
			$id = absint($arrData['id']);
			
			$sql = "SELECT * FROM " . ZMOVIES_TABLE_SHORTCODE . " WHERE id = $id ";
			//echo '<br/>' . $sql;
			$result = $wpdb->get_row($sql, ARRAY_A);
		}
		return $result;
	}
	
	public function save_item($arrData = array(), $options = array()){
		
		global $zController, $wpdb;
		//echo '<br/>' . __METHOD__;		
		$action = $arrData['action'];
		
		$slug = sanitize_title($arrData['name']);
		// echo '<pre>';
		// print_r($arrData);
		// echo '</pre>';
		// die();		
		$contentData['orderby']   = $arrData['orderby'];
		$contentData['ordering']  = $arrData['ordering'];
		$contentData['items']     = $arrData['items'];
		$contentData['position']  = $arrData['position'];
		$contentData['page_title']= $arrData['page_title'];
		$contentData['filter_where']= $arrData['filter_where'];
		$contentData['video_16'] = $arrData['video_16'];
		$contentData['load_type'] = $arrData['load_type'];
		$contentData['first_post_per_page'] = $arrData['first_post_per_page'];
		

		if($arrData['total_item'] > 0 && is_numeric($arrData['total_item'])){		    
		    $contentData['total_item']= $arrData['total_item'];
		}else {
		    $contentData['total_item'] = 100;
		}

		if($arrData['scroll_total_item'] > 0 && is_numeric($arrData['scroll_total_item'])){		    
		    $contentData['scroll_total_item']= $arrData['scroll_total_item'];
		}else {
		    $contentData['scroll_total_item'] = 50;
		}

		if($arrData['page_title'] == 'site_video'){
			$contentData['post_category'] = $arrData['post_category'];
			$contentData['tax_input'] = $arrData['tax_input'];
		}
		
		
		$data = array(
				'name' 				=> $arrData['name'],
				'content' 			=> serialize($contentData),
				'created_by' 		=> get_current_user_id()
				);
		$format = array('%s','%s','%d');		
		
		if($action == 'add'){
		    $data['code'] = '';
		    $wpdb->insert(ZMOVIES_TABLE_SHORTCODE, $data);
    		
    		$insertID   = $wpdb->insert_id;
    		if($insertID > 0 ){
        		$code       = '[zvideo_sc id="' . $insertID . '" total_item="'.$contentData['total_item'].'" position="' . $contentData['position'] . '" name="' . $slug . '"]';
        		$data  = array('code'=>$code);
        		$wpdb->update(ZMOVIES_TABLE_SHORTCODE,$data,array('id'=>$insertID));
    		}
		}else if ($action == 'edit'){
		    $code       = '[zvideo_sc id="' . absint($arrData['id']) . '" total_item="'.$contentData['total_item'].'" position="' . $contentData['position'] . '" name="' . $slug . '"]';		    
		    $data['code'] = $code;
			$where = array('id'=> absint($arrData['id']));
			$wpdb->update(ZMOVIES_TABLE_SHORTCODE,$data,$where);
		}
	 
	}
	

	public function column_default($item, $column_name){
	
		return $item[$column_name];
	}
	
	
	public function column_name($item){
	
		global $zController;
	
		$page = $zController->getParams('page');
	
		$name = 'security_code';
	
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
	
    public function getData($taxonomy){      
        $tax_terms = get_terms($taxonomy,['hide_empty' => false]);        
        $options   = [];
        if(!empty($tax_terms)){
            foreach ($tax_terms as $key => $val){
                $options[$val->slug] = $val->name;
            }
        }       
        return $options;    
    }

    public function count_items(){
	    global $wpdb; 
	    $query = $wpdb->get_var('SELECT COUNT(id) FROM '.ZMOVIES_TABLE_SHORTCODE);
	    $wpdb->flush();
	    return $query;
	} 

	
}