<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Email_Notification
 * @smallpackage Email_Notification/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="en_form_wrap">
    <form id="en_form" action="" method="post">
        <?php
        global $en_alert, $wpdb;

        if(empty($en_alert) && isset($_COOKIE['en_alert'])){
            $en_alert = ['type' => 'success', 'msg' => $_COOKIE['en_alert']];
        }
        
        if(!empty($en_alert) && is_array($en_alert) && sizeof($en_alert) === 2){
            echo '<div class="en_alert '.$en_alert['type'].'">';
            echo '<p>'.$en_alert['msg'].'</p>';
            echo '</div>';
        }

        $email = ((isset($_POST['en_email'])) ? $_POST['en_email'] : '');
        $date = ((isset($_POST['en_date'])) ? $_POST['en_date'] : '');
        $text = ((isset($_POST['en_texts'])) ? $_POST['en_texts'] : '');

        $isUpdate = false;
        if(isset($_GET['date']) && !empty($_GET['date'])){
            $registration = intval($_GET['date']);
            
            if(is_int($registration)){
                $currentRow = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}email_notifications WHERE ID = $registration");
                if($currentRow){
                    $isUpdate = true;
                    $email = $currentRow->email;
                    $date = date("Y-m", strtotime($currentRow->date));
                    $text = $currentRow->text;
                }
            }
        }

        ?>
        <div class="en_input_box">
            <label for="en_email">Email<small>*</small></label>
            <input type="email" required name="en_email" oninvalid="setCustomValidity('Bitte geben Sie Ihre gültige E-Mail-Adresse ein!')" oninput="setCustomValidity('')" id="en_email" value="<?php echo $email ?>">
        </div>
        <div class="en_input_box">
            <label for="en_date">Datum<small>*</small></label>
            <input type="month" required name="en_date" oninvalid="setCustomValidity('Bitte wählen Sie ein Datum!')" oninput="setCustomValidity('')" id="en_date" value="<?php echo $date ?>">
        </div>
        <div class="en_input_box">
            <label for="en_texts">Kennzeichen<small>*</small></label>
            <input type="text" <?php echo (($isUpdate)?'readonly':'') ?> name="en_texts" required oninvalid="setCustomValidity('Bitte schreiben Sie einen Text zu diesem Datum!')" oninput="setCustomValidity('')" value="<?php echo $text ?>" id="en_texts">
        </div>
        <div class="en_input_box">
            <a target="_blank" href="https://gutachterteam-hamburg.de/agb">AGB</a>
            <div class="agb_check">
                <label class="check">
                    <input type="checkbox" value="" name="en_agb_check" id="en_agb_check" />
                    <span class="agb_checkbox"></span>
                </label>
                <label for="en_agb_check">Ich Stimme den</label>
            </div>
        </div>

        <?php
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
        $curPageURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        if($isUpdate){
            $registration = intval($_GET['date']);
            ?>
            <input type="hidden" name="en_date_id" value="<?php echo $registration ?>">
            <?php
        }
        ?>
        <input type="hidden" name="en_current_page" value="<?php echo $curPageURL ?>">
        <?php wp_nonce_field( 'en_form_nonce', 'en_nonce', true, true ) ?>
        <input type="submit" name="<?php echo (($isUpdate) ? 'en_date_update': 'en_date_register') ?>" class="register-button" value="Eintragen">
    </form>
</div>