<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 12/29/17
 * Time: 3:10 PM
 */

namespace WPQueueTasks;

/**
 * Class Utils - Contains some utility methods that can be used across classes
 *
 * @package WPQueueTasks
 */
class Utils {

	/**
	 * Returns true if debug is on, false if it is off
	 *
	 * @return bool
	 */
	public static function debug_on() {

		if ( defined( 'WP_QUEUE_TASKS_DEBUG' ) ) {
			return (bool) WP_QUEUE_TASKS_DEBUG;
		} else {
			return false;
		}

	}

	/**
	 * Sets a transient to prevent a queue from being updated, it it's already being processed.
	 * Using transients, because they are backed my memcache, therefore faster to read/write to.
	 *
	 * @uses set_transient()
	 *
	 * @param string $queue_name Name of the queue to set a lock for
	 * @access public
	 * @return void
	 */
	public static function lock_queue_process( $queue_name ) {
		// Set the expiration to 5 minutes, just in case something goes wrong processing the queue,
		// it doesn't just stay locked forever.
		set_transient( 'wpqt_queue_lock_' . $queue_name, 'locked', 5 * MINUTE_IN_SECONDS );
	}

	/**
	 * Delete's the queue lock transient to allow other processes to process the queue
	 *
	 * @uses delete_transient()
	 *
	 * @param string $queue_name Name of the queue to unlock
	 * @access public
	 * @return void
	 */
	public static function unlock_queue_process( $queue_name ) {
		delete_transient( 'wpqt_queue_lock_' . $queue_name );
	}

	/**
	 * Checks to see if the queue is already being processed
	 *
	 * @uses get_transient()
	 *
	 * @param string $queue_name Name of the queue to check for a lock
	 * @access public
	 * @return mixed
	 */
	public static function is_queue_process_locked( $queue_name ) {
		return get_transient( 'wpqt_queue_lock_' . $queue_name );
	}

}
