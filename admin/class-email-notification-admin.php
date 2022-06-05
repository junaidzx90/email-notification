<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Email_Notification
 * @subpackage Email_Notification/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Email_Notification
 * @subpackage Email_Notification/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class Email_Notification_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/email-notification-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/email-notification-admin.js', array( 'jquery' ), $this->version, false );

	}

	function en_admin_menu(){
		add_menu_page( 'Email notifications', 'Email notifications', 'manage_options', 'en-notifications', [$this, 'notification_table'], 'dashicons-email-alt2', 45 );
		add_submenu_page( 'en-notifications', "Settings", "Settings", "manage_options", "en-settings", [$this, "en_setting_page"], null );

		add_settings_section( 'general_en_opt_section', '', '', 'general_opt_en_page' );
		
		add_settings_field( 'en_form_shortcodes', 'Shortcodes', [$this, 'en_form_shortcodes_cb'], 'general_opt_en_page','general_en_opt_section' );
		// Default before/after days
		add_settings_field( 'default_before_after_days', 'Default before/after days', [$this, 'default_before_after_days_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'default_before_after_days' );
		// Date selection range
		add_settings_field( 'en_date_selection_range', 'Date selection range', [$this, 'en_date_selection_range_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_date_selection_range' );
		// Form submission success message
		add_settings_field( 'form_submission_success_msg', 'Form submission success message', [$this, 'form_submission_success_msg_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'form_submission_success_msg' );
		// Email subject
		add_settings_field( 'en_email_subject', 'Email subject', [$this, 'en_email_subject_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_subject' );
		// Email heading
		add_settings_field( 'en_email_heading', 'Email heading', [$this, 'en_email_heading_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_heading' );
		// Email logo_url
		add_settings_field( 'en_email_logo_url', 'Email logo url', [$this, 'en_email_logo_url_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_logo_url' );
		// Email body
		add_settings_field( 'en_email_body', 'Email body', [$this, 'en_email_body_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_body' );
		// Email footer
		add_settings_field( 'en_email_footer', 'Email footer', [$this, 'en_email_footer_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_footer' );
	}

	function en_form_shortcodes_cb(){
		echo '<p><code>[notification_reg_form]</code></p>';
		echo '<p><code>[unsubscribe]</code></p>';
	}
	function default_before_after_days_cb(){
		echo '<input type="number" name="default_before_after_days" id="default_before_after_days" placeholder="-1" value="'.get_option('default_before_after_days', '-1').'">';
	}
	function en_date_selection_range_cb(){
		echo '<input type="number" min="1" name="en_date_selection_range" placeholder="1" value="'.get_option('en_date_selection_range',1).'">';
		echo '<p>Date selection prevent from the current date.</p>';
	}
	function form_submission_success_msg_cb(){
		echo '<input type="text" placeholder="The new date is successfully {action}!" class="widefat" name="form_submission_success_msg" value="'.get_option('form_submission_success_msg').'">';
		echo '<p>{action} can be: registered / updated</p>';
	}
	function en_email_subject_cb(){
		echo '<input type="text" class="widefat" name=="en_email_subject" placeholder="Your registered date is expired!" value="'.get_option('en_email_subject').'">';
		echo '<p>Use <code>{text}, {username}, {date}</code> as a placeholder.</p>';
	}
	function en_email_heading_cb(){
		echo '<input type="text" class="widefat" name=="en_email_heading" placeholder="Dear {username}," value="'.get_option('en_email_heading').'">';
		echo '<p>Use <code>{text}, {username}, {date}</code> as a placeholder.</p>';
	}
	function en_email_logo_url_cb(){
		echo '<input type="url" name="en_email_logo_url" id="en_email_logo_url" class="widefat" value="'.get_option('en_email_logo_url').'">';
	}
	function en_email_body_cb(){
		wp_editor( wpautop( get_option('en_email_body') ), 'en_email_body', [
			'media_buttons' => false,
			'editor_height' => 200,
			'textarea_name' => 'en_email_body'
		] );
		echo '<p>Use <code>{text}, {username}, {date}, {update_link}, {unsubscribe_link}</code> as a placeholder.</p>';
	}
	function en_email_footer_cb(){
		echo '<input type="text" class="widefat" placeholder="@2022 '.get_bloginfo( 'name' ).' inc | {unsubscribe_link}" name="en_email_footer" value="'.get_option('en_email_footer').'">';
		echo '<p>Use <code>{unsubscribe_link}</code> for unsubcribe link.</p>';
	}

	function notification_table(){
		$notifications = new Email_Notify_Table();
		?>
		<div class="wrap" id="notifications-table">
			<h3 class="heading3">Notifications</h3>
			<hr>

			<?php
			if((isset($_GET['page']) && $_GET['page'] === 'en-notifications') && (isset($_GET['action']) && $_GET['action'] === 'edit') && isset($_GET['notification'])){
				require_once plugin_dir_path( __FILE__ )."partials/email-notification-admin-display.php";
			}else{
				?>
				<form action="" method="post">
					<?php
					$notifications->prepare_items();
					$notifications->display();
					?>
				</form>
				<?php
			}
			?>
		</div>
		<?php
	}

	function en_setting_page(){
		echo '<h3>Settings</h3><hr>';
		echo '<form style="width: 75%" action="options.php" method="post">';
		settings_fields( 'general_en_opt_section' );
		do_settings_sections( 'general_opt_en_page' );
		submit_button(  );
		echo '</form>';
	}

	function date_check($date) {
		$defaultZone = wp_timezone_string();
        if($defaultZone){
            date_default_timezone_set($defaultZone);
        }

        $date1 = new DateTime($date);  //current date or any date
        $date2 = new DateTime(date("Y-m-d"));   //Future date
        
        if(strtotime($date) >= strtotime(date("Y-m-d"))){
            return date("F j, Y", strtotime($date));
        }else{
            return null;
        }
	}

	function email_template($data){
		$template = '<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
			<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
			style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;">
			<tr>
				<td>
					<table style="background-color: #f2f3f8; max-width:670px; margin:0 auto;" width="100%" border="0"
						align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td style="height:80px;">&nbsp;</td>
						</tr>
						<tr>
							<td style="text-align:center;">
								<a href="'.home_url(  ).'" title="logo" target="_blank">
								<img width="60" src="'.$data['logo'].'" title="logo" alt="logo">
							</a>
							</td>
						</tr>
						<tr>
							<td style="height:20px;">&nbsp;</td>
						</tr>
						<tr>
							<td>
								<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
									style="max-width:670px; background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
									<tr>
										<td style="height:40px;">&nbsp;</td>
									</tr>
									<tr>
										<td style="padding:0 35px;">
											<h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:\'Rubik\',sans-serif;">'.$data['heading'].'</h1>
											<p style="font-size:15px; color:#455056; margin:8px 0 0; line-height:24px;">'.wpautop($data['body'], true).'</p>
										</td>
									</tr>
									<tr>
										<td style="height:40px;">&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="height:20px;">&nbsp;</td>
						</tr>
						<tr>
							<td style="text-align:center;">
								<p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">'.$data['footer'].'</strong> </p>
							</td>
						</tr>
						<tr>
							<td style="height:80px;">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
			</table>
		</body>';

		return $template;
	}

	function sent_notification($data){
		$email = $data['email'];
		$subject = $data['subject'];
		$body = $this->email_template($data);

		$headers = array('Content-Type: text/html; charset=UTF-8');
		 
		if(wp_mail( $email, $subject, $body, $headers )){
			return true;
		}
	}
	
	function run_cronjob(){
		if ( ! wp_next_scheduled( 'en_notifications' ) ) {
			wp_schedule_event( time(), 'every_hour', 'en_notifications');
		}
	}

	function en_notifications_schedule( $schedules ) {
		// Adds once weekly to the existing schedules.
		$schedules['every_hour'] = array(
			'interval' => 1 * HOUR_IN_SECONDS,
			'display'  => __( 'Hourly' ),
		);
		return $schedules;
	}
	
	function send_email_notifications(){
		global $wpdb;
		$defaultZone = wp_timezone_string();
		if($defaultZone){
			date_default_timezone_set($defaultZone);
		}

		$currentDate = date("Y-m-d");
		$upcommingDates = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}email_notifications WHERE notify = '$currentDate' AND notified != '$currentDate'");

		$register_post_id = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}posts WHERE post_content LIKE '%[notification_reg_form]%'");
		$unsubscribe_post_id = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}posts WHERE post_content LIKE '%[unsubscribe]%'");

		if($upcommingDates){
			foreach($upcommingDates as $upcomming){
				$did = $upcomming->ID;
				$updateLink = (($register_post_id) ? get_the_permalink( $register_post_id ): null);
				$updateLink .= "?date=".$upcomming->ID;
				$updateLink = str_replace(["http://", "https://"], "", $updateLink);

				$unsubscribeLink = (($unsubscribe_post_id) ? get_the_permalink( $unsubscribe_post_id ): null);
				$unsubscribeLink .= "?date=".base64_encode($upcomming->ID);

				$email_subject = ((get_option('en_email_subject')) ? get_option('en_email_subject') : 'Your registered date is expired!');
				$email_heading = ((get_option('en_email_heading')) ? get_option('en_email_heading') : 'Dear {username},');
				$logo_url = ((get_option('en_email_logo_url')) ? get_option('en_email_logo_url') : '');
				$email_body = ((get_option('en_email_body')) ? get_option('en_email_body') : '');
				$email_footer = ((get_option('en_email_footer')) ? get_option('en_email_footer') : '@2022 '.get_bloginfo( 'name' ).' inc | {unsubscribe_link}');

				$username = explode("@", $upcomming->email)[0];
				$email_subject = str_replace("{username}", ucfirst($username), $email_subject );
				$email_subject = str_replace("{text}", '<strong>'.$upcomming->text.'</strong>', $email_subject );
				$email_subject = str_replace("{date}", '<strong>'.date("F j, Y", strtotime($upcomming->notify)).'</strong>', $email_subject );

				$email_heading = str_replace("{username}", '<strong>'.ucfirst($username).'</strong>', $email_heading );
				$email_heading = str_replace("{text}", '<strong>'.$upcomming->text.'</strong>', $email_heading );
				$email_heading = str_replace("{date}", '<strong>'.date("F j, Y", strtotime($upcomming->notify)).'</strong>', $email_heading );

				$email_body = str_replace("{username}", '<strong>'.ucfirst($username).'</strong>', $email_body );
				$email_body = str_replace("{text}", '<strong>'.$upcomming->text.'</strong>', $email_body );
				$email_body = str_replace("{date}", '<strong>'.date("F j, Y", strtotime($upcomming->notify)).'</strong>', $email_body );
				$email_body = str_replace("{update_link}", $updateLink, $email_body );
				$email_body = str_replace("{unsubscribe_link}", $unsubscribeLink, $email_body );

				$email_footer = str_replace("{unsubscribe_link}", '<a href="'.$unsubscribeLink.'">Click here to unsubscribe.</a>', $email_footer );
				
				$data = [
					'email' => $upcomming->email,
					'subject' => $email_subject,
					'logo' => $logo_url,
					'heading' => $email_heading,
					'body' => $email_body,
					'footer' => $email_footer
				];

				if($this->sent_notification($data)){

					$wpdb->update($wpdb->prefix.'email_notifications', array(
						'notified' => $currentDate
					), array('ID' => $did), array('%s'), array('%d'));

				}
			}
		}
	}
}
