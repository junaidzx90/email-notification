<?php
global $wpdb;
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Email_Notification
 * @subpackage Email_Notification/admin/partials
 */


 if(isset($_POST['submit'])){
    if(wp_verify_nonce( $_POST['en_admin_nonce'], 'en_admin_form_nonce' )){
        $email = ((isset($_POST['en_email'])) ? $_POST['en_email']: '');
        $date = ((isset($_POST['original_date'])) ? $_POST['original_date']: '');
        $notification = intval($_POST['notification_id']);
        global $wpdb;
        $table = $wpdb->prefix.'email_notifications';
        $data['email'] = $email;
        $data['date'] = date("Y-m-d", strtotime($date));
        $wpdb->update($table, $data, ['ID' => $notification, 'email' => $email], ['%s', '%s'],['%d'] );
    }
 }
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="email_notifications">
    <form action="" method="post">
        <?php
        $defaultZone = wp_timezone_string();
        if($defaultZone){
            date_default_timezone_set($defaultZone);
        }

        if(!empty($_GET['notification'])){
            $id = intval($_GET['notification']);
            $notification = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}email_notifications WHERE ID = $id");
            if($notification && !is_wp_error( $notification )){
                ?>
                <div class="en_input_box">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="en_email" value="<?php echo $notification->email ?>">
                </div>
                <div class="en_input_box">
                    <label for="original_date">Date</label>
                    <input type="month" id="original_date" name="original_date" value="<?php echo date("Y-m", strtotime($notification->date)) ?>">
                </div>
                <div class="en_input_box">
                    <?php
                        $beforeAfter = ((get_option( 'default_before_after_months' )) ? get_option( 'default_before_after_months' ) : '');
                        $modifiedDate = $notification->date;
                        
                        if($beforeAfter < 0 || $beforeAfter > 0){
                            $modifiedDate = calculateDate($beforeAfter, $modifiedDate);
                        }
                    ?>
                    <p>Notify date <strong><?php echo date("F, Y", strtotime($modifiedDate)) ?></strong></p>
                </div>
                <div class="en_input_box">
                    <label for="register_date">Registered Date</label>
                    <input type="datetime" id="register_date" readonly value="<?php echo date("Y-m-d, h:i:a", strtotime($notification->created)) ?>">
                </div>
                <div class="en_input_box">
                    <label for="en_text">Text</label>
                    <input type="text" name="en_text" id="en_text"  readonly value="<?php echo $notification->text ?>">
                </div>
                <input type="hidden" name="notification_id" value="<?php echo ((isset($_GET['notification'])) ? $_GET['notification'] : '') ?>">
                <?php wp_nonce_field( 'en_admin_form_nonce', 'en_admin_nonce', true, true ) ?>
                <?php echo get_submit_button( 'Save changes', 'button-primary' ) ?>
                <?php
            }else{
                ob_start();
                wp_safe_redirect( admin_url( 'admin.php?page=en-notifications' ) );
                exit;
            }
        }else{
            ob_start();
            wp_safe_redirect( admin_url( 'admin.php?page=en-notifications' ) );
            exit;
        }
        ?>
    </form>

</div>