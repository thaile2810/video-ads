<?php
namespace Zendvn\Inc;

class CustomPost{
    
    public $_options = null;
    
    public function __construct($options = null){
        $this->_options = $options;
    }
    
    /*
     * Thiết lập các giá trị cần thiết cho Custom Post
     * $options     array   meta_box_id : ID của hộp MetaBox (ex: zmovies-movies)
     *                      prefix_id   : Phần này sẽ được sử dụng để tạo ra 
     *                                    tên của các phần tử Input trong Form (ex: zmovies-movies-)
     *                      prefix_key  : phần này sẽ được sử dụng để tạo ra 
     *                                    các option_key lưu trong bảng wp_postmeta (ex: zmovies_movies_)
     *                      type_post   : Tên của kiểu Custom Post (ex: zmovies)
     */
    public function setOptions($options){       
        $this->_options = $options;
    }
    
    /*
     * Phương thức bổ xung các selectbox để lọc dữ liệu cho List Item
     *
     * @param   obj     Một đối tượng nào đó chứa phương thức cần thiết
     * @param   string  Tên của phương thức chứa mã xử lý
     * @param   int     Độ ưu tiên xuất hiện trong luồng xử lý của WP
     * @param   int     Số lượng tham số chuyền vào Hook
     *
     */
    public function addItemFilters($obj, $func,$priority = 10, $accepted_args = 1){
    
        //add_action('restrict_manage_posts', array($this,'re_category_list')); 
        $hook = 'restrict_manage_posts';
        add_action($hook, array($obj,$func),$priority,$accepted_args);
    }
    
   /*
     * Phương thức chỉnh sửa lại đối tượng WP_Query trong List Item
     *
     * @param   obj     Một đối tượng nào đó chứa phương thức cần thiết
     * @param   string  Tên của phương thức chứa mã xử lý
     * @param   int     Độ ưu tiên xuất hiện trong luồng xử lý của WP
     * @param   int     Số lượng tham số chuyền vào Hook
     *
     */
    public function modifyQuery($obj, $func,$priority = 10, $accepted_args = 1){
    
        //add_action('pre_get_posts', array($this,'modify_query'));
        $hook = 'pre_get_posts';        
        add_action($hook, array($obj,$func),$priority,$accepted_args);
    }
    
    /*
     * Phương thức thêm link sort cho các cột trong List Item
     *
     * @param   obj     Một đối tượng nào đó chứa phương thức cần thiết
     * @param   string  Tên của phương thức chứa mã xử lý
     * @param   int     Độ ưu tiên xuất hiện trong luồng xử lý của WP
     * @param   int     Số lượng tham số chuyền vào Hook
     *
     */
    public function addSortCols($obj, $func,$priority = 10, $accepted_args = 1){
    
        //add_filter('manage_posts_columns', array($obj,$func),$priority, $accepted_args);
        $hook = 'manage_edit-' . $this->getPostType() . '_sortable_columns';        
        add_filter($hook, array($obj,$func),$priority,$accepted_args);
    }
    
    /*
     * Phương thức thêm giá trị vào cho các cột mới bổ xung
     *
     * @param   obj     Một đối tượng nào đó chứa phương thức cần thiết
     * @param   string  Tên của phương thức chứa mã xử lý
     * @param   int     Độ ưu tiên xuất hiện trong luồng xử lý của WP
     * @param   int     Số lượng tham số chuyền vào Hook
     *
     */
    public function addColValues($obj, $func,$priority = 10, $accepted_args = 1){
        
        //add_filter('manage_posts_columns', array($obj,$func),$priority, $accepted_args);
        $hook = 'manage_' . $this->getPostType() . '_posts_custom_column';
        add_action($hook, array($obj,$func),$priority,$accepted_args);
    }
    
    /*
     * Phương thức thêm cột cho List Item sử dụng[manage_posts_columns] hook
     *
     * @param   obj     Một đối tượng nào đó chứa phương thức cần thiết
     * @param   string  Tên của phương thức chứa mã xử lý
     * @param   int     Độ ưu tiên xuất hiện trong luồng xử lý của WP
     * @param   int     Số lượng tham số chuyền vào Hook
     *
     */
    public function addColumns($obj, $func,$priority = 10, $accepted_args = 1){
        add_filter('manage_posts_columns', array($obj,$func),$priority, $accepted_args);
    }
    
    /*
     * Phương thức khai báo MetBox cho [add_meta_boxes] hook
     * 
     * @param   obj     Một đối tượng nào đó chứa phương thức cần thiết
     * @param   string  Tên của phương thức chứa mã xử lý
     * @param   int     Độ ưu tiên xuất hiện trong luồng xử lý của WP
     * @param   int     Số lượng tham số chuyền vào Hook
     * 
     */
    public function addMetaBoxHook($obj, $func,$priority = 10, $accepted_args = 1){        
        add_action('add_meta_boxes', array($obj,$func),$priority, $accepted_args);
    }
    
    /*
     * Phương thức giúp chỉnh sửa lại <FORM> Tag của phần POST
     * [post_edit_form_tag] hook
     * 
     * @param   obj     Một đối tượng nào đó chứa phương thức cần thiết
     * @param   string  Tên của phương thức chứa mã xử lý
     * @param   int     Độ ưu tiên xuất hiện trong luồng xử lý của WP
     * @param   int     Số lượng tham số chuyền vào Hook
     * 
     */
    public function modifyFormHook($obj, $func,$priority = 10, $accepted_args = 1){
        //add_action('post_edit_form_tag', array($this,'update_edit_form'));
        add_action('post_edit_form_tag', array($obj, $func),$priority, $accepted_args);
    }
    
    /*
     * Phương thức lấy ra tên của Custom Post
     * 
     * return $this->_options['type_post']
     */
    public function getPostType(){
        return  $this->_options['type_post'];
    }
    
    /*
     * Phương thức lấy ra tên của meta_box_id
     * 
     *  return $this->_options['meta_box_id']
     */
    public function getMetaBoxId(){
        return $this->_options['meta_box_id'];
    }
    
    /*
     * Create ID for HTML input
     */
    public function create_id($val){
        return $this->_options['prefix_id'] . '-' . $val;
    }
    
    /*
     * Create key để lưu vào trong bảng wp_postmeta
     */
    public function create_key($val){
        return $this->_options['prefix_key'] . $val;
    }
}