<?php
namespace Zmovies\Helper;

class CustomRole{
    
    public static $instance;

    const ADMINISTRATOR_ROLE_KEY = 'administrator';
    const EDITOR_ROLE_KEY = 'editor';
    const AUTHOR_ROLE_KEY = 'author';
    const CONTRIBUTOR_ROLE_KEY = 'contributor';
    const SUBSCRIBER_ROLE_KEY = 'subscriber';

    const SUSPEND_ROLE_KEY      = 'zmovies_suspend';
    const SUSPEND_ROLE_NAME     = 'Suspend';
    const BANNED_ROLE_KEY       = 'zmovies_banned';
    const BANNED_ROLE_NAME      = 'Banned';
    
    public function __construct($pluginFile){
        self::$instance = $this;
        register_activation_hook($pluginFile, [self::$instance,'add_custom_role']);
        register_deactivation_hook($pluginFile, [self::$instance,'remove_custom_role']);

        $phpFile = basename($_SERVER['SCRIPT_NAME']);
        if($phpFile == 'users.php'){
            add_action( 'init', array($this, 'custom_bulk_action_change_role') );
        }
        
        add_action('profile_update', array($this, 'custom_user_profile_fields_update'));
        add_filter( 'manage_users_columns', array($this, 'new_modify_user_table') );
        add_filter( 'manage_users_custom_column', array($this, 'new_modify_user_table_row'), 10, 3 );
    }
    
    public function add_custom_role(){
        global $wp_roles;
        $list_roles =  $wp_roles->role_names;
        foreach ($list_roles as $key => $value) {
            $list_roles[$key] = get_role($key)->capabilities;
        }
        add_role( self::SUSPEND_ROLE_KEY, self::SUSPEND_ROLE_NAME,  $list_roles[self::SUBSCRIBER_ROLE_KEY] );
        add_role( self::BANNED_ROLE_KEY, self::BANNED_ROLE_NAME, $list_roles[self::SUBSCRIBER_ROLE_KEY] );
    }

    public function remove_custom_role(){
        remove_role(SUSPEND_ROLE_KEY);
        remove_role(BANNED_ROLE_KEY);
    }

    public function update_role_user_and_post($user_id, $option){
    	global $wpdb;
        if($user_id != 2){
        	if($option['role'] == 'zmovies_suspend'){
        		$data = array(
        			'post_status' => 'pending'
       			);
       			$table = $wpdb->posts;
       			$where = array(
       				'post_author' => $user_id
       			);
       			$wpdb->update( $table, $data, $where );
        	}elseif($option['role'] == 'zmovies_banned'){
        		$table = $wpdb->posts;
        		$where = array(
       				'post_author' => $user_id
       			);
       			$wpdb->delete( $table, $where );
        	}
        }
    }

    public function custom_bulk_action_change_role(){
        if( isset($_REQUEST['changeit']) && $_REQUEST['new_role'] != ''){
            check_admin_referer('bulk-users');
            $option['role'] = $_REQUEST['new_role'];
            foreach ($_REQUEST['users'] as $key => $value) {
                if($value != 2){
                    $this->update_role_user_and_post($value, $option);
                }
            }
            
        }
    }

    public  function custom_user_profile_fields_update( $user_id )
    {
        global $current_user;

        if(in_array('administrator', $current_user->roles) && get_current_user_id() != $user_id){
            if($user_id != 2){
                $this->update_role_user_and_post($user_id, $_REQUEST);
            }
        }
    }

    public function new_modify_user_table( $column ) {
        $column['reported_16'] = 'Reported 16+';
        $column['reported_die'] = 'Reported die';
        $column['report_16'] = 'Spam/All RP 16+';
        $column['report_die'] = 'Spam/All RP die';
        return $column;
    }

    public function new_modify_user_table_row( $val, $column_name, $user_id ) {
        switch ($column_name) {
            case 'report_16' :
                $user_report_16 = @get_user_meta( $user_id, 'zvideos_video_report_16_post', true );
                $user_spam_report_16 = @get_user_meta( $user_id, 'zvideos_video_spam_report_16', true );

                $count_report_16 = '';
                if($user_report_16 == ''){
                    $count_report_16 = 0;
                }else{
                    $count_report_16 = count($user_report_16);
                }

                $count_spam_16 = '';
                if($user_spam_report_16 == ''){
                    $count_spam_16 = 0;
                }else{
                    $count_spam_16 = count($user_spam_report_16);
                }
                if($count_report_16 == 0){
                    $percent = 0;
                }else{
                    $percent = $count_spam_16/$count_report_16*100;
                }

                return $count_spam_16.' / '.$count_report_16.'<br>'.'Spam: '.absint($percent).'%';
                break;
            case 'report_die' :
                $user_report_die = @get_user_meta( $user_id, 'zvideos_video_report_die_post', true );
                $user_spam_report_die = @get_user_meta( $user_id, 'zvideos_video_spam_report_die', true );

                $count_report_die = '';
                if($user_report_die == ''){
                    $count_report_die = 0;
                }else{
                    $count_report_die = count($user_report_die);
                }

                $count_spam_die = '';
                if($user_spam_report_die == ''){
                    $count_spam_die = 0;
                }else{
                    $count_spam_die = count($user_spam_report_die);
                }
                if($count_report_die == 0){
                    $percent = 0;
                }else{
                    $percent = $count_spam_die/$count_report_die*100;
                }

                return $count_spam_die.' / '.$count_report_die.'<br>'.'Spam: '.absint($percent).'%';
                break;
            case 'video_post_die':
                $author_post_die = @get_user_meta( $user_id, 'zvideos_video_upload_post_die', true );
                ($author_post_die == '') ? $author_post_die = 0 : $author_post_die = count($author_post_die);
                return $author_post_die;
                break;
            case 'reported_16':
                global $wpdb;
                $sql = "SELECT COUNT($wpdb->posts.ID) 
                        FROM $wpdb->posts, $wpdb->postmeta
                        WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
                        AND $wpdb->postmeta.meta_key LIKE '%zvideos_video_report_16_by_user%'
                        AND $wpdb->posts.post_author = '".$user_id."'";
                $reported_16 = $wpdb->get_var($sql);
                return $reported_16;
                break;
            case 'reported_die':
                global $wpdb;
                $sql = "SELECT COUNT($wpdb->posts.ID) 
                        FROM $wpdb->posts, $wpdb->postmeta
                        WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
                        AND $wpdb->postmeta.meta_key LIKE '%zvideos_video_report_die_by_user%'
                        AND $wpdb->posts.post_author = '".$user_id."'";
                $reported_die = $wpdb->get_var($sql);
                return $reported_die;
                break;
            default: ;
        }
        return $val;
    }
    
}