<?php
global $zController;

$model = $zController->getModel('CronEvent');

$new_tabs = array(
    'cron'     => __( 'Add Cron Event', ZMOVIES_MENU_SLUG ),
    'php-cron' => __( 'Add PHP Cron Event', ZMOVIES_MENU_SLUG ),
);
$modify_tabs = array(
    'cron'     => __( 'Modify Cron Event', ZMOVIES_MENU_SLUG ),
    'php-cron' => __( 'Modify PHP Cron Event', ZMOVIES_MENU_SLUG ),
);
$new_links = array(
    'cron'     => admin_url( 'tools.php?page=crontrol_admin_manage_page&action=new-cron' ) . '#crontrol_form',
    'php-cron' => admin_url( 'tools.php?page=crontrol_admin_manage_page&action=new-php-cron' ) . '#crontrol_form',
);
    $helper_text = sprintf(
        esc_html__( 'Cron events trigger actions in your code. A cron event added using the form below needs a corresponding action hook somewhere in code, perhaps the %1$s file in your theme.', ZMOVIES_MENU_SLUG ),
        '<code>functions.php</code>'
    );
if ( is_array( $existing ) ) {
    $other_fields  = wp_nonce_field( "edit-cron_{$existing['hookname']}_{$existing['sig']}_{$existing['next_run']}", '_wpnonce', true, false );
    $other_fields .= sprintf( '<input name="original_hookname" type="hidden" value="%s" />',
        esc_attr( $existing['hookname'] )
    );
    $other_fields .= sprintf( '<input name="original_sig" type="hidden" value="%s" />',
        esc_attr( $existing['sig'] )
    );
    $other_fields .= sprintf( '<input name="original_next_run" type="hidden" value="%s" />',
        esc_attr( $existing['next_run'] )
    );
    $existing['args'] = $is_php ? $existing['args']['code'] : wp_json_encode( $existing['args'] );
    $existing['next_run'] = date( 'Y-m-d H:i:s', $existing['next_run'] );
    $action = $is_php ? 'edit_php_cron' : 'edit_cron';
    $button = $is_php ? $modify_tabs['php-cron'] : $modify_tabs['cron'];
    $show_edit_tab = true;
} else {
    $other_fields = wp_nonce_field( 'new-cron', '_wpnonce', true, false );
    $existing = array( 'hookname' => '', 'hookcode' => '', 'args' => '', 'next_run' => 'now', 'schedule' => false );
    $action = $is_php ? 'new_php_cron' : 'new_cron';
    $button = $is_php ? $new_tabs['php-cron'] : $new_tabs['cron'];
    
    $show_edit_tab = false;
}
?>
		<div id="crontrol_form" class="wrap narrow">
			<h2 class="nav-tab-wrapper">
				<a href="<?php echo esc_url( $new_links['cron'] ); ?>" class="nav-tab<?php if ( ! $show_edit_tab && ! $is_php ) { echo ' nav-tab-active'; } ?>"><?php echo esc_html( $new_tabs['cron'] ); ?></a>
				<?php if ( current_user_can( 'edit_files' ) ) { ?>
					<a href="<?php echo esc_url( $new_links['php-cron'] ); ?>" class="nav-tab<?php if ( ! $show_edit_tab && $is_php ) { echo ' nav-tab-active'; } ?>"><?php echo esc_html( $new_tabs['php-cron'] ); ?></a>
				<?php } ?>
				<?php if ( $show_edit_tab ) { ?>
					<span class="nav-tab nav-tab-active"><?php echo esc_html( $button ); ?></span>
				<?php } ?>
			</h2>
			<p><?php echo $helper_text; // WPCS:: XSS ok. ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'tools.php?page=crontrol_admin_manage_page' ) ); ?>">
				<?php echo $other_fields; // WPCS:: XSS ok. ?>
				<table class="form-table"><tbody>
					<tr>
						<th valign="top" scope="row"><label for="hookname"><?php esc_html_e( 'Hook name:', ZMOVIES_MENU_SLUG ); ?></label></th>
						<td><input type="text" class="regular-text" id="hookname" name="hookname" value="<?php echo esc_attr( $existing['hookname'] ); ?>"/></td>
					</tr>
					<tr>
						<th valign="top" scope="row"><label for="args"><?php esc_html_e( 'Arguments:', ZMOVIES_MENU_SLUG ); ?></label></th>
						<td>
							<input type="text" class="regular-text" id="args" name="args" value="<?php echo esc_attr( $existing['args'] ); ?>"/>
							<p class="description"><?php esc_html_e( "e.g. [25], ['asdf'], or ['i','want',25,'cakes']", ZMOVIES_MENU_SLUG ); ?></p>
						</td>
					</tr>
					<tr>
						<th valign="top" scope="row"><label for="next_run"><?php esc_html_e( 'Next run (UTC):', ZMOVIES_MENU_SLUG ); ?></label></th>
						<td>
							<input type="text" class="regular-text" id="next_run" name="next_run" value="<?php echo esc_attr( $existing['next_run'] ); ?>"/>
							<p class="description"><?php esc_html_e( "e.g. 'now', 'tomorrow', '+2 days', or '25-02-2020 12:34:00'", ZMOVIES_MENU_SLUG ); ?></p>
						</td>
					</tr><tr>
						<th valign="top" scope="row"><label for="schedule"><?php esc_html_e( 'Event schedule:', ZMOVIES_MENU_SLUG ); ?></label></th>
						<td>
							<?php $model->schedules_dropdown( $existing['schedule'] ); ?>
						</td>
					</tr>
				</tbody></table>
				<p class="submit"><input type="submit" class="button-primary" value="<?php echo esc_attr( $button ); ?>" name="<?php echo esc_attr( $action ); ?>"/></p>
			</form>
		</div>