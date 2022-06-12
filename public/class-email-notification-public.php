<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Email_Notification
 * @subpackage Email_Notification/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Email_Notification
 * @subpackage Email_Notification/public
 * @author     Developer Junayed <admin@easeare.com>
 */
class Email_Notification_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( 'notification_reg_form', [$this, 'notification_registration_form'] );
		add_shortcode( 'unsubscribe', [$this, 'notification_unsubscribe'] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/email-notification-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/email-notification-public.js', array( 'jquery' ), $this->version, true );

	}

	function notification_registration_form(){
		ob_start();
		require plugin_dir_path( __FILE__ )."partials/email-notification-public-display.php";
		return ob_get_clean();
	}

	function notification_unsubscribe(){
		ob_start();
		global $wpdb;
		$register_post_id = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}posts WHERE post_content LIKE '%[notification_reg_form]%'");
		$registerLink = (($register_post_id) ? get_the_permalink( $register_post_id ): null);

		if(isset($_GET['date']) && !empty($_GET['date'])){
			$encodedid = base64_decode($_GET['date']);
            $registration = intval($encodedid);
			if($wpdb->query("DELETE FROM {$wpdb->prefix}email_notifications WHERE ID = $registration")){
				echo '<p style="text-align: center;">Sie haben sich erfolgreich abgemeldet.</p>';
			}else{
				wp_safe_redirect( $registerLink );
				exit;
			}
		}
		return ob_get_clean();
	}

	function form_submission(){
		if(isset($_POST['en_date_register'])){
			if(wp_verify_nonce( $_POST['en_nonce'], 'en_form_nonce' )){
				$defaultZone = wp_timezone_string();
				if($defaultZone){
					date_default_timezone_set($defaultZone);
				}

				global $en_alert, $wpdb;
				$email = ((isset($_POST['en_email'])) ? $_POST['en_email'] : '');
				$date = ((isset($_POST['en_date'])) ? $_POST['en_date'] : '');
				$text = ((isset($_POST['en_texts'])) ? $_POST['en_texts'] : '');

				if(!empty($email) && !empty($date) && !empty($text)){
					$email = sanitize_email( $email );
					$text = sanitize_text_field( stripslashes($text) );
					$date = date("Y-m-d", strtotime($date));
					
					$table = $wpdb->prefix.'email_notifications';
					if(!$wpdb->get_var("SELECT ID FROM $table WHERE text = '$text'")){
						$data['email'] = $email;
						$data['date'] = $date;
						$data['text'] = $text;
						$wpdb->insert( $table, $data );
	
						if(!is_wp_error( $wpdb )){
							do_action( 'sent_email_instantly' );
							
							$alert = 'Du hast deine TÜV Benachrichtigung erfolgreich eingetragen!';
							if(get_option('form_submission_success_msg')){
								$alert = str_replace("{action}", "eingetragen.", get_option('form_submission_success_msg'));
							}

							setcookie("en_alert", $alert, time()+30);
							ob_start();
							wp_safe_redirect( $_POST['en_current_page'] );
							exit;
						}
					}else{
						$en_alert = ['type' => 'error', 'msg' => 'Dieser Termin ist bereits registriert!'];
					}
				}else{
					$en_alert = ['type' => 'error', 'msg' => 'Pflichtfelder fehlen!'];
				}
			}
		}

		if(isset($_POST['en_date_update'])){
			if(wp_verify_nonce( $_POST['en_nonce'], 'en_form_nonce' )){
				$defaultZone = wp_timezone_string();
				if($defaultZone){
					date_default_timezone_set($defaultZone);
				}
				
				global $en_alert, $wpdb;
				$email = ((isset($_POST['en_email'])) ? $_POST['en_email'] : '');
				$date = ((isset($_POST['en_date'])) ? $_POST['en_date'] : '');
				$en_date_id = ((isset($_POST['en_date_id'])) ? intval($_POST['en_date_id']) : null);

				if(!empty($email) && !empty($date) && $en_date_id > 0){
					$email = sanitize_email( $email );
					$date = date("Y-m-d", strtotime($date));

					$table = $wpdb->prefix.'email_notifications';
					$data['email'] = $email;
					$data['date'] = $date;
					$wpdb->update($table, $data, ['ID' => $en_date_id, 'email' => $email], ['%s', '%s'],['%d'] );

					if(!is_wp_error( $wpdb )){
						$alert = 'Du hast deine TÜV Benachrichtigung erfolgreich upgedated!';
						if(get_option('form_submission_success_msg')){
							$alert = str_replace("{action}", "upgedated.", get_option('form_submission_success_msg'));
						}

						setcookie("en_alert", $alert, time()+30);
						ob_start();
						wp_safe_redirect( $_POST['en_current_page'] );
						exit;
					}
				
				}else{
					$en_alert = ['type' => 'error', 'msg' => 'Pflichtfelder fehlen!'];
				}
			}
		}
	}
}
