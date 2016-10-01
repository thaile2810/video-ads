<?php
namespace Zmovies\Models;


class CronEvent{
//     public static $intance;

    public $_key = 'zvideo_cron_event';
    public function __construct() {

		//$plugin_file = plugin_basename( __FILE__ );

		//add_filter( "plugin_action_links_{$plugin_file}", array( $this, 'plugin_action_links' ), 10, 4 );

		//register_activation_hook( __FILE__, array( $this, 'action_activate' ) );

		add_filter( 'cron_schedules',    array( $this, 'filter_cron_schedules' ) );
		add_action( 'crontrol_cron_job', array( $this, 'action_php_cron_event' ) );
	}

	/**
	 * Evaluates the provided code using eval.
	 */
	public function action_php_cron_event( $code ) {
		eval( $code );
	}

	/**
	 * Handles any POSTs made by the plugin. Run using the 'init' action.
	 */
	public function action_handle_posts() {
		if ( isset( $_POST['new_cron'] ) ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You are not allowed to add new cron events.', ZMOVIES_MENU_SLUG ) );
			}
			check_admin_referer( 'new-cron' );
			$in_next_run = $in_schedule = $in_hookname = $in_hookcode = '';
			extract( wp_unslash( $_POST ), EXTR_PREFIX_ALL, 'in' );
			$in_args = json_decode( $in_args, true );
			$this->add_cron( $in_next_run, $in_schedule, $in_hookname, $in_args );
			$redirect = array(
				'page'             => 'crontrol_admin_manage_page',
				'crontrol_message' => '5',
				'crontrol_name'    => urlencode( $in_hookname ),
			);
			wp_redirect( add_query_arg( $redirect, admin_url( 'admin.php' ) ) );
			exit;

		} else if ( isset( $_POST['new_php_cron'] ) ) {
			if ( ! current_user_can( 'edit_files' ) ) {
				wp_die( esc_html__( 'You are not allowed to add new PHP cron events.', ZMOVIES_MENU_SLUG ) );
			}
			check_admin_referer( 'new-cron' );
			extract( wp_unslash( $_POST ), EXTR_PREFIX_ALL, 'in' );
			$args = array( 'code' => $in_hookcode );
			$this->add_cron( $in_next_run, $in_schedule, 'crontrol_cron_job', $args );
			$redirect = array(
				'page'             => 'crontrol_admin_manage_page',
				'crontrol_message' => '5',
				'crontrol_name'    => urlencode( $in_hookname ),
			);
			wp_redirect( add_query_arg( $redirect, admin_url( 'admin.php' ) ) );
			exit;

		} else if ( isset( $_POST['edit_cron'] ) ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You are not allowed to edit cron events.', ZMOVIES_MENU_SLUG ) );
			}
			$in_original_hookname = $in_original_sig = $in_original_next_run = '';
			extract( wp_unslash( $_POST ), EXTR_PREFIX_ALL, 'in' );
			check_admin_referer( "edit-cron_{$in_original_hookname}_{$in_original_sig}_{$in_original_next_run}" );
			$in_args = json_decode( $in_args, true );
			$i = $this->delete_cron( $in_original_hookname, $in_original_sig, $in_original_next_run );
			$i = $this->add_cron( $in_next_run, $in_schedule, $in_hookname, $in_args );
			$redirect = array(
				'page'             => 'crontrol_admin_manage_page',
				'crontrol_message' => '4',
				'crontrol_name'    => urlencode( $in_hookname ),
			);
			wp_redirect( add_query_arg( $redirect, admin_url( 'tools.php' ) ) );
			exit;

		} else if ( isset( $_POST['edit_php_cron'] ) ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You are not allowed to edit cron events.', ZMOVIES_MENU_SLUG ) );
			}

			extract( wp_unslash( $_POST ), EXTR_PREFIX_ALL, 'in' );
			check_admin_referer( "edit-cron_{$in_original_hookname}_{$in_original_sig}_{$in_original_next_run}" );
			$args['code'] = $in_hookcode;
			$args = array( 'code' => $in_hookcode );
			$i = $this->delete_cron( $in_original_hookname, $in_original_sig, $in_original_next_run );
			$i = $this->add_cron( $in_next_run, $in_schedule, 'crontrol_cron_job', $args );
			$redirect = array(
				'page'             => 'crontrol_admin_manage_page',
				'crontrol_message' => '4',
				'crontrol_name'    => urlencode( $in_hookname ),
			);
			wp_redirect( add_query_arg( $redirect, admin_url( 'tools.php' ) ) );
			exit;

		} else if ( isset( $_POST['new_schedule'] ) ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You are not allowed to add new cron schedules.', ZMOVIES_MENU_SLUG ) );
			}
			check_admin_referer( 'new-sched' );
			$name = wp_unslash( $_POST['internal_name'] );
			$interval = wp_unslash( $_POST['interval'] );
			$display = wp_unslash( $_POST['display_name'] );

			// The user entered something that wasn't a number.
			// Try to convert it with strtotime
			if ( ! is_numeric( $interval ) ) {
				$now = time();
				$future = strtotime( $interval, $now );
				if ( false === $future || -1 == $future || $now > $future ) {
					$redirect = array(
						'page'             => 'crontrol_admin_options_page',
						'crontrol_message' => '7',
						'crontrol_name'    => urlencode( $interval ),
					);
					wp_redirect( add_query_arg( $redirect, admin_url( 'options-general.php' ) ) );
					exit;
				}
				$interval = $future - $now;
			} else if ( $interval <= 0 ) {
				$redirect = array(
					'page'             => 'crontrol_admin_options_page',
					'crontrol_message' => '7',
					'crontrol_name'    => urlencode( $interval ),
				);
				wp_redirect( add_query_arg( $redirect, admin_url( 'options-general.php' ) ) );
				exit;
			}

			$this->add_schedule( $name, $interval, $display );
			$redirect = array(
				'page'             => 'crontrol_admin_options_page',
				'crontrol_message' => '3',
				'crontrol_name'    => urlencode( $name ),
			);
			wp_redirect( add_query_arg( $redirect, admin_url( 'options-general.php' ) ) );
			exit;

		} else if ( isset( $_GET['action'] ) && 'delete-sched' == $_GET['action'] ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You are not allowed to delete cron schedules.', ZMOVIES_MENU_SLUG ) );
			}
			$id = wp_unslash( $_GET['id'] );
			check_admin_referer( "delete-sched_{$id}" );
			$this->delete_schedule( $id );
			$redirect = array(
				'page'             => 'crontrol_admin_options_page',
				'crontrol_message' => '2',
				'crontrol_name'    => urlencode( $id ),
			);
			wp_redirect( add_query_arg( $redirect, admin_url( 'options-general.php' ) ) );
			exit;

		} else if ( isset( $_GET['action'] ) && 'delete-cron' == $_GET['action'] ) {
			

		} else if ( isset( $_GET['action'] ) && 'run-cron' == $_GET['action'] ) {
			
		}
	}
	public function runCron(){
	    
	    if ( ! current_user_can( 'manage_options' ) ) {
	        wp_die( esc_html__( 'You are not allowed to run cron events.', ZMOVIES_MENU_SLUG ) );
	    }
	    global $zController;
	    $page       = $zController->getParams('page');
	    $id = wp_unslash( $_GET['id'] );
	    $sig = wp_unslash( $_GET['sig'] );
	    check_admin_referer( "run-cron_{$id}_{$sig}" );
	    if ( $this->run_cron( $id, $sig ) ) {
	        $redirect = array(
	            'page'                         => $page,
	            $this->getMetaKey('message')   => '1',
	            $this->getMetaKey('name')      => urlencode( $id ),
	        );
	        wp_redirect( add_query_arg( $redirect, admin_url( 'admin.php' ) ) );
	        exit;
	    } else {
	        $redirect = array(
	            'page'             => $page,
	            $this->getMetaKey('message') => '8',
	            $this->getMetaKey('name')    => urlencode( $id ),
	        );
	        wp_redirect( add_query_arg( $redirect, admin_url( 'admin.php' ) ) );
	        exit;
	    }
	}
	public function deleteCron(){
	    if ( ! current_user_can( 'manage_options' ) ) {
	        wp_die( esc_html__( 'You are not allowed to delete cron events.', ZMOVIES_MENU_SLUG ) );
	    }
	    
	    global $zController;
	    $page       = $zController->getParams('page');
	    
	    $id = wp_unslash( $_GET['id'] );
	    $sig = wp_unslash( $_GET['sig'] );
	    $next_run = $_GET['next_run'];
	    check_admin_referer( "delete-cron_{$id}_{$sig}_{$next_run}" );
	    if ( $this->delete_cron( $id, $sig, $next_run ) ) {
	        $redirect = array(
	            'page'             => $page,
	            $this->getMetaKey('message') => '6',
	            $this->getMetaKey('name')    => urlencode( $id ),
	        );
	        wp_redirect( add_query_arg( $redirect, admin_url( 'admin.php' ) ) );
	        exit;
	    } else {
	        $redirect = array(
	            'page'             => $page,
	            $this->getMetaKey('message') => '7',
	            $this->getMetaKey('name')    => urlencode( $id ),
	        );
	        wp_redirect( add_query_arg( $redirect, admin_url( 'admin.php' ) ) );
	        exit;
	    
	    };
	}
	
	/**
	 * Executes a cron event immediately.
	 *
	 * Executes an event by scheduling a new single event with the same arguments.
	 *
	 * @param string $hookname The hookname of the cron event to run
	 */
	public function run_cron( $hookname, $sig ) {
		$crons = _get_cron_array();
		foreach ( $crons as $time => $cron ) {
			if ( isset( $cron[ $hookname ][ $sig ] ) ) {
				$args = $cron[ $hookname ][ $sig ]['args'];
				delete_transient( 'doing_cron' );
				wp_schedule_single_event( time() - 1, $hookname, $args );
				spawn_cron();
				return true;
			}
		}
		return false;
	}
	// AidenDam
	public function singleRun( $hookname) {
	    $crons = _get_cron_array();
	    foreach ( $crons as $time => $cron ) {
	        if ( isset( $cron[ $hookname ]) ) {
	            $key = key($cron[ $hookname ]);
	            $args = $cron[ $hookname ][ $key]['args'];
	            delete_transient( 'doing_cron' );
	            wp_schedule_single_event( time() - 1, $hookname, $args );
	            spawn_cron();
	            return true;
	        }
	    }
	    return false;
	}
	/**
	 * Adds a new cron event.
	 *
	 * @param string $next_run A human-readable (strtotime) time that the event should be run at
	 * @param string $schedule The recurrence of the cron event
	 * @param string $hookname The name of the hook to execute
	 * @param array $args Arguments to add to the cron event
	 */
	public function add_cron( $next_run, $schedule, $hookname, $args ) {
		$next_run = strtotime( $next_run );
		if ( false === $next_run || -1 == $next_run ) {
			$next_run = time();
		}
		if ( ! is_array( $args ) ) {
			$args = array();
		}
		if ( '_oneoff' == $schedule ) {
			return wp_schedule_single_event( $next_run, $hookname, $args ) === null;
		} else {
			return wp_schedule_event( $next_run, $schedule, $hookname, $args ) === null;
		}
	}

	/**
	 * Deletes a cron event.
	 *
	 * @param string $name The hookname of the event to delete.
	 */
	public function delete_cron( $to_delete, $sig, $next_run ) {
		$crons = _get_cron_array();
		if ( isset( $crons[ $next_run ][ $to_delete ][ $sig ] ) ) {
			$args = $crons[ $next_run ][ $to_delete ][ $sig ]['args'];
			wp_unschedule_event( $next_run, $to_delete, $args );
			return true;
		}
		return false;
	}

	/**
	 * Adds a new custom cron schedule.
	 *
	 * @param string $name     The internal name of the schedule
	 * @param int    $interval The interval between executions of the new schedule
	 * @param string $display  The display name of the schedule
	 */
	public function add_schedule( $name, $interval, $display ) {
		$old_scheds = get_option( 'crontrol_schedules', array() );
		$old_scheds[ $name ] = array( 'interval' => $interval, 'display' => $display );
		update_option( 'crontrol_schedules', $old_scheds );
	}

	/**
	 * Deletes a custom cron schedule.
	 *
	 * @param string $name The internal_name of the schedule to delete.
	 */
	public function delete_schedule( $name ) {
		$scheds = get_option( 'crontrol_schedules', array() );
		unset( $scheds[ $name ] );
		update_option( 'crontrol_schedules', $scheds );
	}

	/**
	 * Sets up the plugin environment upon first activation.
	 *
	 * Run using the 'activate_' action.
	 */
	public function action_activate() {
		add_option( 'crontrol_schedules', array() );

		// if there's never been a cron event, _get_cron_array will return false
		if ( _get_cron_array() === false ) {
			_set_cron_array( array() );
		}
	}

	/**
	 * Gives WordPress the plugin's set of cron schedules.
	 *
	 * Called by the 'cron_schedules' filter.
	 *
	 * @param array $scheds The current cron schedules. Usually an empty array.
	 * @return array The existing cron schedules along with the plugin's schedules.
	 */
	public function filter_cron_schedules( $scheds ) {
		$new_scheds = get_option( 'crontrol_schedules', array() );
		return array_merge( $new_scheds, $scheds );
	}

	/**
	 * Displays the options page for the plugin.
	 */

	/**
	 * Gets a sorted (according to interval) list of the cron schedules
	 */
	public function get_schedules() {
		$schedules = wp_get_schedules();
		uasort( $schedules, create_function( '$a, $b', 'return $a["interval"] - $b["interval"];' ) );
		return $schedules;
	}

	/**
	 * Displays a dropdown filled with the possible schedules, including non-repeating.
	 *
	 * @param boolean $current The currently selected schedule
	 */
	public function schedules_dropdown( $current = false ) {
		$schedules = $this->get_schedules();
		?>
		<select class="postform" name="schedule" id="schedule">
		<option <?php selected( $current, '_oneoff' ); ?> value="_oneoff"><?php esc_html_e( 'Non-repeating', ZMOVIES_MENU_SLUG ); ?></option>
		<?php foreach ( $schedules as $sched_name => $sched_data ) { ?>
			<option <?php selected( $current, $sched_name ); ?> value="<?php echo esc_attr( $sched_name ); ?>">
				<?php
				printf(
					'%s (%s)',
					esc_html( $sched_data['display'] ),
					esc_html( $this->interval( $sched_data['interval'] ) )
				);
				?>
			</option>
		<?php } ?>
		</select>
		<?php
	}

	/**
	 * Gets the status of WP-Cron functionality on the site by performing a test spawn. Cached for one hour when all is well.
	 *
	 */
	public function test_cron_spawn( $cache = true ) {
		global $wp_version;

		if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {
			return new \WP_Error( 'disable_wp_cron', __( 'The DISABLE_WP_CRON constant is set to true. WP-Cron spawning is disabled.', ZMOVIES_MENU_SLUG ) );
		}

		if ( defined( 'ALTERNATE_WP_CRON' ) && ALTERNATE_WP_CRON ) {
			return true;
		}

		$cached_status = get_transient( 'wp-cron-test-ok' );

		if ( $cache && $cached_status ) {
			return true;
		}

		$sslverify     = version_compare( $wp_version, 4.0, '<' );
		$doing_wp_cron = sprintf( '%.22F', microtime( true ) );

		$cron_request = apply_filters( 'cron_request', array(
			'url'  => site_url( 'wp-cron.php?doing_wp_cron=' . $doing_wp_cron ),
			'key'  => $doing_wp_cron,
			'args' => array(
				'timeout'   => 3,
				'blocking'  => true,
				'sslverify' => apply_filters( 'https_local_ssl_verify', $sslverify ),
			),
		) );

		$cron_request['args']['blocking'] = true;

		$result = wp_remote_post( $cron_request['url'], $cron_request['args'] );

		if ( is_wp_error( $result ) ) {
			return $result;
		} else if ( wp_remote_retrieve_response_code( $result ) >= 300 ) {
			return new \WP_Error( 'unexpected_http_response_code', sprintf(
				__( 'Unexpected HTTP response code: %s', ZMOVIES_MENU_SLUG ),
				intval( wp_remote_retrieve_response_code( $result ) )
			) );
		} else {
			set_transient( 'wp-cron-test-ok', 1, 3600 );
			return true;
		}

	}

	/**
	 * Shows the status of WP-Cron functionality on the site. Only displays a message when there's a problem.
	 *
	 */
	public function show_cron_status() {

		$status = $this->test_cron_spawn();

		if ( is_wp_error( $status ) ) {
			?>
			<div id="cron-status-error" class="error">
				<p><?php printf( esc_html__( 'There was a problem spawning a call to the WP-Cron system on your site. This means WP-Cron events on your site may not work. The problem was: %s', ZMOVIES_MENU_SLUG ), '<br><strong>' . esc_html( $status->get_error_message() ) . '</strong>' ); ?></p>
			</div>
			<?php
		}

	}

	public function get_cron_events() {

		$crons  = _get_cron_array();
		$events = array();

		if ( empty( $crons ) ) {
			return new \WP_Error(
				'no_events',
				__( 'You currently have no scheduled cron events.', ZMOVIES_MENU_SLUG )
			);
		}

		foreach ( $crons as $time => $cron ) {
			foreach ( $cron as $hook => $dings ) {
				foreach ( $dings as $sig => $data ) {

					# This is a prime candidate for a Crontrol_Event class but I'm not bothering currently.
					$events[ "$hook-$sig-$time" ] = (object) array(
						'hook'     => $hook,
						'time'     => $time,
						'sig'      => $sig,
						'args'     => $data['args'],
						'schedule' => $data['schedule'],
						'interval' => isset( $data['interval'] ) ? $data['interval'] : null,
					);

				}
			}
		}

		return $events;

	}


	/**
	 * Pretty-prints the difference in two times.
	 *
	 * @param time $older_date
	 * @param time $newer_date
	 * @return string The pretty time_since value
	 * @link http://binarybonsai.com/code/timesince.txt
	 */
	public function time_since( $older_date, $newer_date ) {
		return $this->interval( $newer_date - $older_date );
	}

	public function interval( $since ) {
		// array of time period chunks
		$chunks = array(
			array( 60 * 60 * 24 * 365, _n_noop( '%s year', '%s years', ZMOVIES_MENU_SLUG ) ),
			array( 60 * 60 * 24 * 30, _n_noop( '%s month', '%s months', ZMOVIES_MENU_SLUG ) ),
			array( 60 * 60 * 24 * 7, _n_noop( '%s week', '%s weeks', ZMOVIES_MENU_SLUG ) ),
			array( 60 * 60 * 24, _n_noop( '%s day', '%s days', ZMOVIES_MENU_SLUG ) ),
			array( 60 * 60, _n_noop( '%s hour', '%s hours', ZMOVIES_MENU_SLUG ) ),
			array( 60, _n_noop( '%s minute', '%s minutes', ZMOVIES_MENU_SLUG ) ),
			array( 1, _n_noop( '%s second', '%s seconds', ZMOVIES_MENU_SLUG ) ),
		);

		if ( $since <= 0 ) {
			return __( 'now', ZMOVIES_MENU_SLUG );
		}

		// we only want to output two chunks of time here, eg:
		// x years, xx months
		// x days, xx hours
		// so there's only two bits of calculation below:

		// step one: the first chunk
		for ( $i = 0, $j = count( $chunks ); $i < $j; $i++ ) {
			$seconds = $chunks[ $i ][0];
			$name = $chunks[ $i ][1];

			// finding the biggest chunk (if the chunk fits, break)
			if ( ( $count = floor( $since / $seconds ) ) != 0 ) {
				break;
			}
		}

		// set output var
		$output = sprintf( translate_nooped_plural( $name, $count, ZMOVIES_MENU_SLUG ), $count );

		// step two: the second chunk
		if ( $i + 1 < $j ) {
			$seconds2 = $chunks[ $i + 1 ][0];
			$name2 = $chunks[ $i + 1 ][1];

			if ( ( $count2 = floor( ( $since - ( $seconds * $count ) ) / $seconds2 ) ) != 0 ) {
				// add to output var
				$output .= ' ' . sprintf( translate_nooped_plural( $name2, $count2, ZMOVIES_MENU_SLUG ), $count2 );
			}
		}

		return $output;
	}
    public function getMetaKey($key){
        return $this->_key . '_' . $key;
    }
}