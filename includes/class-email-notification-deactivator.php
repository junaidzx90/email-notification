<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Email_Notification
 * @subpackage Email_Notification/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Email_Notification
 * @subpackage Email_Notification/includes
 * @author     Developer Junayed <admin@easeare.com>
 */
class Email_Notification_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook('en_notifications');
	}

}
