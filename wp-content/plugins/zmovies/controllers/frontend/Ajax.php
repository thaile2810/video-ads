<?php
namespace Zmovies\Controller\Frontend;

class Ajax{
	
	public function __construct(){
		/*==============Ajax Frontend========*/
		add_action('wp_head',array($this,'add_ajax_library'));
		add_action('wp_ajax_process',array($this,'process'));
		add_action('wp_ajax_nopriv_process', array($this,'process'));
		
		//add_action('wp_enqueue_scripts',array($this,'add_js_file'));
		
	}
	
	public function process(){
		
    	
		global $zController,$wpdb,$wp_rewrite;
		
		$params = $zController->getParams();
		
		if($params['func'] == 'search'){
		    $this->search();
		}
		if($params['func'] == 'is-tax'){
		    $this->isTax($params);
		}
		
		if($params['func'] == 'tax-change-status'){
		    $this->taxChangeStatus($params);
		}
		
		if($params['func'] == 'test'){
		    $this->test($params);
		}
		if($params['func'] == 'play'){
		    $this->play($params);
		}
		die();
	}
	public function play($params){
	    
	    if(!class_exists('JwPlayer')) include ZVIDEO_THEME_DIR . DS . 'includes' . DS . 'jwplayer' . DS . 'JwPlayer.php';
	    
	    set_time_limit(3600);
	    
	    $jwPlayer = new \JwPlayer();
	    $jwPlayer->play(@$params['id'],@$params['date'],@$params['auto']);
	}
	public function test($params){
	    global $zController;
	    
        $options       = get_option(ZMOVIES_SETTING_OPTION . '-auto-update', []);
    	$setting       = @$options['keyword'];
    	$category      = $zController->getModel('Category');
    	
    	$categories    = $category->getTerm(null,['task' => 'auto-update']);
    	$categories    = array_column($categories, 'term_id', 'meta_value');
    	
    	$tags = explode(',', $params['ids']);
    	$params        = ['categories' => $categories];
    	
    	/* $filename = ZMOVIES_FILE_PATH . DS . 'logs' . DS . 'bulk-keyword.log';
    	file_put_contents($filename, json_encode($params) . "\n", FILE_APPEND | LOCK_EX); */
    	//set_time_limit(0);
    	$model = $zController->getModel('Video');
    	foreach($tags as $tag_ID ) {
    	    
    	    $terrm = get_term($tag_ID, 'zvideos_youtube_keyword', ARRAY_A);
    	    $params['keyword'] = urlencode($terrm['name']);
    	    $model->keywordAddVideo($params,$setting);
    	    
    	    $filename = ZMOVIES_FILE_PATH . DS . 'logs' . DS . 'bulk-keyword.log';
    	    file_put_contents($filename, $params['keyword']. ' ' . date('Y-m-d H:i:s') . "\n", FILE_APPEND | LOCK_EX);
    	}
    	
	}
	public function search(){
	    
	    $posts         = get_posts(['s' => $_REQUEST['term'] , 'post_status' => ['new', 'publish', 'error']]);
	    $suggestions   = [];
	    
	    global $post;
	    
	    foreach ($posts as $post): setup_postdata($post);
	    
            $link = str_replace('/\s/g', '+', $post->post_title);
            $link = str_replace(['?','!', '-','[',']'], '', $link);
            $suggestions[] = [
                            'label' => esc_html($post->post_title),
                            'link'  => get_search_link($link)
                            ];
	    endforeach;
	    
	    $response = $_GET["callback"] . "(" . json_encode($suggestions) . ")";
	    echo $response;
	    
        die();
	}
	
	public function isTax($params){
	    
	    $data      = @$params['data'];
	    
	    $data      = array_column($data, 'value', 'name');
	   
	    $taxonomy  = $data['taxonomy'];
	    $url       = @$data[$taxonomy . '[url]'];
	    
	    $error = [];
	    
	    global $zController;
	    
	    switch ($taxonomy){
	        case 'zvideos_youtube_user' :
	            $model = $zController->getModel('YoutubeUser');
	            if(!$model->isYoutubeUser($url)){
	                $error[$taxonomy] = 'Please enter youtube user url';
	            }else{
	                if($model->taxExit($url,$data)){
	                    $error[$taxonomy] = 'This youtube user is exist';
	                }
	            }
	            break;
            case 'zvideos_channel' :
                $model = $zController->getModel('Channel');
                if(!$model->isYoutubeChannel($url)){
                    $error[$taxonomy] = 'Please enter youtube channel url';
                }else{
                    if($model->taxExit($url,$data)){
                        $error[$taxonomy] = 'This channel is exist';
                    }
                }
                break;
            case 'zvideos_playlist' :
                $model = $zController->getModel('Playlist');

                if(!$model->isYoutubePlaylist($url)){
     
                    $error[$taxonomy] = 'Please enter youtube playlist url';
                }else{
                    if($model->taxExit($url)){
                        $error[$taxonomy] = 'This playlist is exist';
                    }
                }
                break;
	    }
	    
	    $success = count($error) > 0 ? false : true;
	    
	    echo json_encode(['status' => $success, 'error' => $error]);
	    
	}
	
    public function taxChangeStatus($params){
        $data = $params['data'];
        if((int)$data['term_id'] > 0){
            
            global $zController;
            update_term_meta($data['term_id'], $data['meta_key'], $data['meta_value']);
            
            if($data['meta_value'] == 1 ){
                $status = 0;
                $src = $zController->getImageUrl('icons/active.png');
            }else{
                $status = 1;
                $src = $zController->getImageUrl('icons/inactive.png');
            }
            $meta = array('term_id' => $data['term_id'], 'meta_key' => $data['meta_key'],'meta_value' => $status);
            echo json_encode(['status' => 'success', 'src' => $src, 'meta' => $meta]);
        }else{
            echo json_encode(['status' => 'error']);
        }
        
        
    }
	public function add_ajax_library(){
	
	    $ajax_nonce = wp_create_nonce('ajax-security-code');
	
	    $html = '<script type="text/javascript">';
	    $html .= ' var ajaxurl = "' . admin_url('admin-ajax.php') . '"; ';
	    $html .= ' var security_code = "' . $ajax_nonce . '"; ';
	    $html .= '</script>';
	
	    echo $html;
	}
	

}