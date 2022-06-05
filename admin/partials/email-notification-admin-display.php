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
                    <input type="email" id="email" readonly value="<?php echo $notification->email ?>">
                </div>
                <div class="en_input_box">
                    <label for="original_date">Original Date</label>
                    <input type="date" id="original_date" name="original_date" value="<?php echo date("Y-m-d", strtotime($notification->date)) ?>">
                </div>
                <div class="en_input_box">
                    <label for="notify_date">Before/After days<small>(optional)</small></label>
                    <input type="number" name="before_after_date" value="<?php echo ((get_option('default_before_after_days')) ? get_option('default_before_after_days') : '-1') ?>">
                    <p style="margin: 0">This day range is specific for this date only.</p>
                </div>
                <div class="en_input_box">
                    <?php
                    if(strtotime($notification->notify) > 0 && $this->date_check($notification->notify) !== null){
                        ?>
                        <p>Notification will sent <strong><?php echo $this->date_check($notification->notify) ?></strong></p>
                        <?php
                    }    
                    ?>
                </div>
                <div class="en_input_box">
                    <label for="register_date">Registered Date</label>
                    <input type="datetime" id="register_date" readonly value="<?php echo date("Y-m-d, h:i:a", strtotime($notification->created)) ?>">
                </div>
                <div class="en_input_box">
                    <label for="renew_date">Renew Date</label>
                    <input type="date" id="renew_date" readonly value="<?php echo date("Y-m-d", strtotime($notification->renew)) ?>">
                </div>
                <div class="en_input_box">
                    <label for="description">Text</label>
                    <input type="text" name="description" id="description" value="<?php echo $notification->text ?>">
                </div>
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