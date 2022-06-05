<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Email_Notification
 * @subpackage Email_Notification/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Email_Notification
 * @subpackage Email_Notification/includes
 * @author     Developer Junayed <admin@easeare.com>
 */
class Email_Notification_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
		$email_notifications = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}email_notifications` (
			`ID` INT NOT NULL AUTO_INCREMENT,
			`email` VARCHAR(255) NOT NULL,
			`date` DATE NOT NULL,
			`text` VARCHAR(255) NOT NULL,
			`notify` DATE NOT NULL,
			`renew` DATE NOT NULL,
			`notified` DATE NOT NULL,
			`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`ID`)) ENGINE = InnoDB";
		dbDelta($email_notifications);
	}

}
