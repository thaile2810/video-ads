<?php 

global $zController;

$model = $zController->getModel('CronEvent');
$messages = array(
            	'1' => __( 'Successfully executed the cron event %s', ZMOVIES_MENU_SLUG ),
            	'4' => __( 'Successfully edited the cron event %s', ZMOVIES_MENU_SLUG ),
            	'5' => __( 'Successfully created the cron event %s', ZMOVIES_MENU_SLUG ),
            	'6' => __( 'Successfully deleted the cron event %s', ZMOVIES_MENU_SLUG ),
            	'7' => __( 'Failed to the delete the cron event %s', ZMOVIES_MENU_SLUG ),
            	'8' => __( 'Failed to the execute the cron event %s', ZMOVIES_MENU_SLUG ),
                );
if ( isset( $_GET[$model->getMetaKey('name')]) && isset( $_GET[$model->getMetaKey('message')] ) && isset( $messages[ $_GET[$model->getMetaKey('message')] ] ) ) {
	$hook = wp_unslash( $_GET[$model->getMetaKey('name')] );
	$msg = sprintf( esc_html( $messages[ $_GET[$model->getMetaKey('message')] ] ), '<strong>' . esc_html( $hook ) . '</strong>' );

	printf( '<div id="message" class="updated notice is-dismissible"><p>%s</p></div>', $msg ); // WPCS:: XSS ok.
}
$events         = $model->get_cron_events();

$doing_edit     = ( isset( $_GET['action'] ) && 'edit-cron' == $_GET['action'] ) ? wp_unslash( $_GET['id'] ) : false ;
$time_format    = 'Y-m-d H:i:s';

$tzstring = get_option( 'timezone_string' );
$current_offset = get_option( 'gmt_offset' );

if ( $current_offset >= 0 ) {
	$current_offset = '+' . $current_offset;
}

if ( '' === $tzstring ) {
	$tz = sprintf( 'UTC%s', $current_offset );
} else {
	$tz = sprintf( '%s (UTC%s)', str_replace( '_', ' ', $tzstring ), $current_offset );
}

$model->show_cron_status();

$page       = $zController->getParams('page');
$linkAdd    = admin_url('admin.php?page=' . $page . '&action=add-cron-event') ;
$lblAdd     = __('Add cron event',ZMOVIES_DOMAIN_LANGUAGE);

$page = $zController->getParams('page');
$linkAddPHP = admin_url('admin.php?page=' . $page . '&action=add-php-cron-event') ;
$lblAddPHP = __('Add php cron event',ZMOVIES_DOMAIN_LANGUAGE);
?>
<div class="wrap">
    <h1><?php esc_html_e( 'Zvideo Cron Events', ZMOVIES_MENU_SLUG ); ?>
        <!-- <a href="<?php echo esc_url($linkAdd);?>" class="add-new-h2"><?php echo esc_html__($lblAdd);?></a>
        <a href="<?php echo esc_url($linkAddPHP);?>" class="add-new-h2"><?php echo esc_html__($lblAddPHP);?></a> -->
    </h1>
    <p></p>
    <table class="widefat striped">
        <thead>
        	<tr>
        		<th><?php esc_html_e( 'Hook Name', ZMOVIES_MENU_SLUG ); ?></th>
        		<th><?php esc_html_e( 'Arguments', ZMOVIES_MENU_SLUG ); ?></th>
        		<th><?php esc_html_e( 'Next Run', ZMOVIES_MENU_SLUG ); ?></th>
        		<th><?php esc_html_e( 'Recurrence', ZMOVIES_MENU_SLUG ); ?></th>
        		<th colspan="3">&nbsp;</th>
        	</tr>
        </thead>
        <tbody>
        <?php
        if ( is_wp_error( $events ) ) {
        	?>
        	<tr><td colspan="7"><?php echo esc_html( $events->get_error_message() ); ?></td></tr>
        	<?php
        } else {
        	foreach ( $events as $id => $event ) {
        	    /* echo '<pre>';
        	         print_r($event);
        	    echo '</pre>'; */
        	    if(substr($id, 0,7) !== 'zvideo_'){
        	        continue;
        	    }
        		if ( $doing_edit && $doing_edit == $event->hook && $event->time == $_GET['next_run'] && $event->sig == $_GET['sig'] ) {
        			$doing_edit = array(
        				'hookname' => $event->hook,
        				'next_run' => $event->time,
        				'schedule' => ( $event->schedule ? $event->schedule : '_oneoff' ),
        				'sig'      => $event->sig,
        				'args'     => $event->args,
        			);
        		}
        
        		if ( empty( $event->args ) ) {
        			$args = __( 'None', ZMOVIES_MENU_SLUG );
        		} else {
        			if ( defined( 'JSON_UNESCAPED_SLASHES' ) ) {
        				$args = wp_json_encode( $event->args, JSON_UNESCAPED_SLASHES );
        			} else {
        				$args = stripslashes( wp_json_encode( $event->args ) );
        			}
        		}
        
        		echo '<tr id="cron-' . esc_attr( $id ) . '" class="">';
        
        		if ( 'crontrol_cron_job' == $event->hook ) {
        			echo '<td><em>' . esc_html__( 'PHP Cron', ZMOVIES_MENU_SLUG ) . '</em></td>';
        			echo '<td><em>' . esc_html__( 'PHP Code', ZMOVIES_MENU_SLUG ) . '</em></td>';
        		} else {
        			echo '<td>' . esc_html( $event->hook ) . '</td>';
        			echo '<td>' . esc_html( $args ) . '</td>';
        		}
        
        		echo '<td>';
        		printf( '%s (%s)',
        			esc_html( get_date_from_gmt( date( 'Y-m-d H:i:s', $event->time ), $time_format ) ),
        			esc_html( $model->time_since( time(), $event->time ) )
        		);
        		echo '</td>';
        
        		if ( $event->schedule ) {
        			echo '<td>';
        			echo esc_html( $model->interval( $event->interval ) );
        			echo '</td>';
        		} else {
        			echo '<td>';
        			esc_html_e( 'Non-repeating', ZMOVIES_MENU_SLUG );
        			echo '</td>';
        		}
        
//         		$link = array(
//         			'page'     => $page,
//         			'action'   => 'edit-cron',
//         			'id'       => urlencode( $event->hook ),
//         			'sig'      => urlencode( $event->sig ),
//         			'next_run' => urlencode( $event->time ),
//         		);
//         		$link = add_query_arg( $link, admin_url( 'tools.php' ) ) . '#crontrol_form';
//         		echo "<td><a class='view' href='" . esc_url( $link ) . "'>" . esc_html__( 'Edit', ZMOVIES_MENU_SLUG ) . '</a></td>';
        
        		$link = array(
        			'page'     => $page,
        			'action'   => 'zvideo-run-cron',
        			'id'       => urlencode( $event->hook ),
        			'sig'      => urlencode( $event->sig ),
        			'next_run' => urlencode( $event->time ),
        		);
        		$link = add_query_arg( $link, admin_url( 'admin.php' ) );
        		$link = wp_nonce_url( $link, "run-cron_{$event->hook}_{$event->sig}" );
        		echo "<td><a class='view' href='". esc_url( $link ) ."'>" . esc_html__( 'Run Now', ZMOVIES_MENU_SLUG ) . '</a></td>';
        
        		$link = array(
        			'page'     => $page,
        			'action'   => 'zvideo-delete-cron',
        			'id'       => urlencode( $event->hook ),
        			'sig'      => urlencode( $event->sig ),
        			'next_run' => urlencode( $event->time ),
        		);
        		$link = add_query_arg( $link, admin_url( 'admin.php' ) );
        		$link = wp_nonce_url( $link, "delete-cron_{$event->hook}_{$event->sig}_{$event->time}" );
        		echo "<td><a class='delete' href='".esc_url( $link )."'>" . esc_html__( 'Delete', ZMOVIES_MENU_SLUG ) . '</a></td>';
        
        		echo '</tr>';
        
        	}
        }
        ?>
        </tbody>
    </table>
    
    <div class="tablenav">
    	<p class="description">
    		<?php printf( esc_html__( 'Local timezone is %s', ZMOVIES_MENU_SLUG ), '<code>' . esc_html( $tz ) . '</code>' ); ?>
    		<span id="utc-time"><?php printf( esc_html__( 'UTC time is %s', ZMOVIES_MENU_SLUG ), '<code>' . esc_html( date_i18n( $time_format, false, true ) ) . '</code>' ); ?></span>
    		<span id="local-time"><?php printf( esc_html__( 'Local time is %s', ZMOVIES_MENU_SLUG ), '<code>' . esc_html( date_i18n( $time_format ) ) . '</code>' ); ?></span>
    	</p>
    </div>

</div>
<?php
/* if ( is_array( $doing_edit ) ) {
	$model->show_cron_form( 'crontrol_cron_job' == $doing_edit['hookname'], $doing_edit );
} else {
	$model->show_cron_form( ( isset( $_GET['action'] ) and 'new-php-cron' == $_GET['action'] ), false );
} */
