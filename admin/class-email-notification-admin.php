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
		// Default before/after months
		add_settings_field( 'default_before_after_months', 'Default before/after months', [$this, 'default_before_after_months_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'default_before_after_months' );
		
		// Form submission success message
		add_settings_field( 'form_submission_success_msg', 'Form submission success message', [$this, 'form_submission_success_msg_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'form_submission_success_msg' );
		// Email subject
		add_settings_field( 'en_email_subject', 'Email subject', [$this, 'en_email_subject_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_subject' );
		// Email logo_url
		add_settings_field( 'en_email_logo_url', 'Email logo url', [$this, 'en_email_logo_url_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_logo_url' );
		// Email body
		add_settings_field( 'en_email_body', 'Email body', [$this, 'en_email_body_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_body' );
		// Email footer
		add_settings_field( 'en_email_footer', 'Email footer', [$this, 'en_email_footer_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_footer' );

		add_settings_field( 'devider_line_1', '', [$this, 'devider_line_1_cb'], 'general_opt_en_page','general_en_opt_section' );

		// extra before/after months
		add_settings_field( 'extra_before_after_months1', '1. before/after months', [$this, 'extra_before_after_months1_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'extra_before_after_months1' );
		// Email subject
		add_settings_field( 'en_email_subject1', 'Email subject', [$this, 'en_email_subject1_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_subject1' );
		// Email footer
		add_settings_field( 'en_email_footer1', 'Email footer', [$this, 'en_email_footer1_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_footer1' );

		add_settings_field( 'devider_line_2', '', [$this, 'devider_line_1_cb'], 'general_opt_en_page','general_en_opt_section' );
		// extra before/after months
		add_settings_field( 'extra_before_after_months2', '2. before/after months', [$this, 'extra_before_after_months2_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'extra_before_after_months2' );
		// Email subject
		add_settings_field( 'en_email_subject2', 'Email subject', [$this, 'en_email_subject2_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_subject2' );
		// Email footer
		add_settings_field( 'en_email_footer2', 'Email footer', [$this, 'en_email_footer2_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_footer2' );

		add_settings_field( 'devider_line_3', '', [$this, 'devider_line_1_cb'], 'general_opt_en_page','general_en_opt_section' );
		// extra before/after months
		add_settings_field( 'extra_before_after_months3', '3. before/after months', [$this, 'extra_before_after_months3_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'extra_before_after_months3' );
		// Email subject
		add_settings_field( 'en_email_subject3', 'Email subject', [$this, 'en_email_subject3_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_subject3' );
		// Email footer
		add_settings_field( 'en_email_footer3', 'Email footer', [$this, 'en_email_footer3_cb'], 'general_opt_en_page','general_en_opt_section' );
		register_setting( 'general_en_opt_section', 'en_email_footer3' );

	}

	function en_form_shortcodes_cb(){
		echo '<p><code>[notification_reg_form]</code></p>';
		echo '<p><code>[unsubscribe]</code></p>';
	}
	function default_before_after_months_cb(){
		echo '<input type="number" name="default_before_after_months" id="default_before_after_months" placeholder="--" value="'.get_option('default_before_after_months', '').'">';
	}
	function form_submission_success_msg_cb(){
		echo '<input type="text" placeholder="The new date is successfully {action}!" class="widefat" name="form_submission_success_msg" value="'.get_option('form_submission_success_msg').'">';
		echo '<p>{action} can be: registered / updated</p>';
	}
	function en_email_subject_cb(){
		echo '<input type="text" class="widefat" name="en_email_subject" placeholder="Your registered date is expired!" value="'.get_option('en_email_subject').'">';
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

	function devider_line_1_cb(){
		echo '<hr>';
	}

	function extra_before_after_months1_cb(){
		$data = get_option('extra_before_after_months1');
		$cond = ((is_array($data) && array_key_exists('cond', $data)) ? $data['cond']: '');
		$email = ((is_array($data) && array_key_exists('email', $data)) ? $data['email']: '');
		$text = ((is_array($data) && array_key_exists('text', $data)) ? $data['text']: '');

		echo '<input type="number" name="extra_before_after_months1[cond]" value="'.$cond.'" placeholder="--">&nbsp;';
		echo '<input type="email" name="extra_before_after_months1[email]" value="'.$email.'" placeholder="email">';

		wp_editor( wpautop( $text ), 'extra_before_after_months1', [
			'media_buttons' => false,
			'editor_height' => 200,
			'textarea_name' => 'extra_before_after_months1[text]'
		] );
		echo '<p>Use <code>{text}, {username}, {date}, {update_link}, {unsubscribe_link}</code> as a placeholder.</p>';
	}
	function en_email_subject1_cb(){
		echo '<input type="text" class="widefat" name="en_email_subject1" placeholder="Your registered date is expired!" value="'.get_option('en_email_subject1').'">';
		echo '<p>Use <code>{text}, {username}, {date}</code> as a placeholder.</p>';
	}
	function en_email_footer1_cb(){
		echo '<input type="text" class="widefat" placeholder="@2022 '.get_bloginfo( 'name' ).' inc | {unsubscribe_link}" name="en_email_footer1" value="'.get_option('en_email_footer1').'">';
		echo '<p>Use <code>{unsubscribe_link}</code> for unsubcribe link.</p>';
	}

	
	function extra_before_after_months2_cb(){
		$data = get_option('extra_before_after_months2');
		$cond = ((is_array($data) && array_key_exists('cond', $data)) ? $data['cond']: '');
		$email = ((is_array($data) && array_key_exists('email', $data)) ? $data['email']: '');
		$text = ((is_array($data) && array_key_exists('text', $data)) ? $data['text']: '');

		echo '<input type="number" name="extra_before_after_months2[cond]" value="'.$cond.'" placeholder="--">&nbsp;';
		echo '<input type="email" name="extra_before_after_months2[email]" value="'.$email.'" placeholder="email">';

		wp_editor( wpautop( $text ), 'extra_before_after_months2', [
			'media_buttons' => false,
			'editor_height' => 200,
			'textarea_name' => 'extra_before_after_months2[text]'
		] );
		echo '<p>Use <code>{text}, {username}, {date}, {update_link}, {unsubscribe_link}</code> as a placeholder.</p>';
	}
	function en_email_subject2_cb(){
		echo '<input type="text" class="widefat" name="en_email_subject2" placeholder="Your registered date is expired!" value="'.get_option('en_email_subject2').'">';
		echo '<p>Use <code>{text}, {username}, {date}</code> as a placeholder.</p>';
	}
	function en_email_footer2_cb(){
		echo '<input type="text" class="widefat" placeholder="@2022 '.get_bloginfo( 'name' ).' inc | {unsubscribe_link}" name="en_email_footer2" value="'.get_option('en_email_footer2').'">';
		echo '<p>Use <code>{unsubscribe_link}</code> for unsubcribe link.</p>';
	}
	
	
	function extra_before_after_months3_cb(){
		$data = get_option('extra_before_after_months3');
		$cond = ((is_array($data) && array_key_exists('cond', $data)) ? $data['cond']: '');
		$email = ((is_array($data) && array_key_exists('email', $data)) ? $data['email']: '');
		$text = ((is_array($data) && array_key_exists('text', $data)) ? $data['text']: '');

		echo '<input type="number" name="extra_before_after_months3[cond]" value="'.$cond.'" placeholder="--">&nbsp;';
		echo '<input type="email" name="extra_before_after_months3[email]" value="'.$email.'" placeholder="email">';
		wp_editor( wpautop( $text ), 'extra_before_after_months3', [
			'media_buttons' => false,
			'editor_height' => 200,
			'textarea_name' => 'extra_before_after_months3[text]'
		] );
		echo '<p>Use <code>{text}, {username}, {date}, {update_link}, {unsubscribe_link}</code> as a placeholder.</p>';
	}
	function en_email_subject3_cb(){
		echo '<input type="text" class="widefat" name="en_email_subject3" placeholder="Your registered date is expired!" value="'.get_option('en_email_subject3').'">';
		echo '<p>Use <code>{text}, {username}, {date}</code> as a placeholder.</p>';
	}
	function en_email_footer3_cb(){
		echo '<input type="text" class="widefat" placeholder="@2022 '.get_bloginfo( 'name' ).' inc | {unsubscribe_link}" name="en_email_footer3" value="'.get_option('en_email_footer3').'">';
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
		$year = date("Y", strtotime($currentDate));
		$month = date("m", strtotime($currentDate));

		$upcommingDates = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}email_notifications WHERE notified != '$currentDate'");

		$register_post_id = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}posts WHERE post_content LIKE '%[notification_reg_form]%'");
		$unsubscribe_post_id = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}posts WHERE post_content LIKE '%[unsubscribe]%'");

		if($upcommingDates){
			foreach($upcommingDates as $upcomming){
				$upDate = $upcomming->date;
				$sentEmail = null;
				$emailSubject = null;
				$emailFooter = null;
				$bodyText = null;
				$action = false;
				// Default
				if(!$action){
					$beforeAfter = ((get_option( 'default_before_after_months' )) ? get_option( 'default_before_after_months' ) : '');
					if($beforeAfter < 0 || $beforeAfter > 0){
						$currDate = get_notify_date($beforeAfter, $upDate);
						if(strtotime(date("Y-m", $currDate)) === strtotime(date("Y-m", $currentDate))){
							$action = true;
						}
					}
				}

				if(!$action){
					$currDate1 = null;
					$data1 = get_option('extra_before_after_months1');
					$cond1 = ((is_array($data1) && array_key_exists('cond', $data1)) ? $data1['cond']: '');
					$sentEmail = ((is_array($data1) && array_key_exists('email', $data1)) ? $data1['email']: null);
					$bodyText = ((is_array($data1) && array_key_exists('text', $data1)) ? $data1['text']: null);
					$emailSubject = get_option( 'en_email_subject1' );
					$emailFooter = get_option( 'en_email_footer1' );

					if($cond1 < 0 || $cond1 > 0){
						$currDate1 = get_notify_date($cond1, $upDate);
						if(strtotime(date("Y-m", $currDate1)) === strtotime(date("Y-m", $currentDate))){
							$action = true;
						}
					}
				}
				if(!$action){
					$currDate2 = null;
					$data2 = get_option('extra_before_after_months2');
					$cond2 = ((is_array($data2) && array_key_exists('cond', $data2)) ? $data2['cond']: '');
					$sentEmail = ((is_array($data2) && array_key_exists('email', $data2)) ? $data2['email']: null);
					$bodyText = ((is_array($data2) && array_key_exists('text', $data2)) ? $data2['text']: null);
					$emailSubject = get_option( 'en_email_subject2' );
					$emailFooter = get_option( 'en_email_footer2' );

					if($cond2 < 0 || $cond2 > 0){
						$currDate2 = get_notify_date($cond2, $upDate);
						if(strtotime(date("Y-m", $currDate2)) === strtotime(date("Y-m", $currentDate))){
							$action = true;
						}
					}
				}
				if(!$action){
					$currDate3 = null;
					$data3 = get_option('extra_before_after_months3');
					$cond3 = ((is_array($data3) && array_key_exists('cond', $data3)) ? $data3['cond']: '');
					$sentEmail = ((is_array($data3) && array_key_exists('email', $data3)) ? $data3['email']: null);
					$bodyText = ((is_array($data3) && array_key_exists('text', $data3)) ? $data3['text']: null);
					$emailSubject = get_option( 'en_email_subject3' );
					$emailFooter = get_option( 'en_email_footer3' );

					if($cond3 < 0 || $cond3 > 0){
						$currDate3 = get_notify_date($cond3, $upDate);
						if(strtotime(date("Y-m", $currDate3)) === strtotime(date("Y-m", $currentDate))){
							$action = true;
						}
					}
				}
				if(!$action){
					if(strtotime(date("Y-m", $upDate)) === strtotime(date("Y-m", $currentDate))){
						$action = true;
					}
				}
				
				if($action){
					$did = $upcomming->ID;
					$text = $upcomming->text;
					$updateLink = (($register_post_id) ? get_the_permalink( $register_post_id ): null);
					$updateLink .= "?date=".$upcomming->ID;
					$updateLink = str_replace(["http://", "https://"], "", $updateLink);

					$unsubscribeLink = (($unsubscribe_post_id) ? get_the_permalink( $unsubscribe_post_id ): null);
					$unsubscribeLink .= "?date=".base64_encode($upcomming->ID);

					$email_subject = ((get_option('en_email_subject')) ? get_option('en_email_subject') : 'Your registered date is expired!');
					if($emailSubject !== null){
						$email_subject = $emailSubject;
					}
					$logo_url = ((get_option('en_email_logo_url')) ? get_option('en_email_logo_url') : '');
					$email_body = ((get_option('en_email_body')) ? get_option('en_email_body') : '');
					if($bodyText !== null){
						$email_body = $bodyText;
					}

					$email_footer = ((get_option('en_email_footer')) ? get_option('en_email_footer') : '@2022 '.get_bloginfo( 'name' ).' inc | {unsubscribe_link}');
					if($emailFooter !== null){
						$email_footer = $emailFooter;
					}

					$username = explode("@", $upcomming->email)[0];
					$email_subject = str_replace("{username}", ucfirst($username), $email_subject );
					$email_subject = str_replace("{text}", '<strong>'.$text.'</strong>', $email_subject );
					$email_subject = str_replace("{date}", '<strong>'.date("F, Y", strtotime($upcomming->date)).'</strong>', $email_subject );

					$email_body = str_replace("{username}", '<strong>'.ucfirst($username).'</strong>', $email_body );
					$email_body = str_replace("{text}", '<strong>'.$text.'</strong>', $email_body );
					$email_body = str_replace("{date}", '<strong>'.date("F, Y", strtotime($upcomming->date)).'</strong>', $email_body );
					$email_body = str_replace("{update_link}", $updateLink, $email_body );
					$email_body = str_replace("{unsubscribe_link}", $unsubscribeLink, $email_body );

					$email_footer = str_replace("{unsubscribe_link}", '<a href="'.$unsubscribeLink.'">Click here to unsubscribe.</a>', $email_footer );
					
					$data = [
						'email' => (($sentEmail !== null) ? $sentEmail : $upcomming->email),
						'subject' => $email_subject,
						'logo' => $logo_url,
						'body' => $email_body,
						'footer' => $email_footer
					];

					if($this->sent_notification($data)){

						en_auto_renew_dates(); // Renew

						$wpdb->update($wpdb->prefix.'email_notifications', array(
							'notified' => $currentDate
						), array('ID' => $did), array('%s'), array('%d'));

					}
				}
				
			}
		}
	}
}
