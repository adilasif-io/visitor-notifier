<?php
// Function to create the admin settings page
function visitor_notification_settings_page() {
    ?>
<div class="wrap">
    <h2>Visitor Notification Settings</h2>
    <form method="post" action="options.php">
        <?php settings_fields('visitor_notification_settings'); ?>
        <?php do_settings_sections('visitor_notification_settings'); ?>
        <?php submit_button(); ?>
    </form>
</div>
<?php
}

// Register settings
function visitor_notification_register_settings() {
    register_setting('visitor_notification_settings', 'visitor_notification_to_email');
    register_setting('visitor_notification_settings', 'visitor_notification_cc_email');
    register_setting('visitor_notification_settings', 'visitor_notification_email_subject');
}

// Add settings sections and fields
function visitor_notification_settings_fields() {
    add_settings_section('visitor_notification_email_settings_section', 'Email Settings', 'visitor_notification_email_settings_section_callback', 'visitor_notification_settings');

    add_settings_field('visitor_notification_to_email', 'To Email', 'visitor_notification_to_email_callback', 'visitor_notification_settings', 'visitor_notification_email_settings_section');
    add_settings_field('visitor_notification_cc_email', 'CC Email', 'visitor_notification_cc_email_callback', 'visitor_notification_settings', 'visitor_notification_email_settings_section');
    add_settings_field('visitor_notification_email_subject', 'Email Subject', 'visitor_notification_email_subject_callback', 'visitor_notification_settings', 'visitor_notification_email_settings_section');
}

// Callback function for email settings section
function visitor_notification_email_settings_section_callback() {
    echo 'Configure email settings for visit notifications';
}

// Callback function for 'To Email' field
function visitor_notification_to_email_callback() {
    $to_email = get_option('visitor_notification_to_email');
    echo "<input type='text' name='visitor_notification_to_email' value='$to_email' />";
}

// Callback function for 'CC Email' field
function visitor_notification_cc_email_callback() {
    $cc_email = get_option('visitor_notification_cc_email');
    echo "<input type='text' name='visitor_notification_cc_email' value='$cc_email' />";
}

// Callback function for 'Email Subject' field
function visitor_notification_email_subject_callback() {
    $email_subject = get_option('visitor_notification_email_subject');
    echo "<input type='text' name='visitor_notification_email_subject' value='$email_subject' />";
}

// Add settings page to admin menu
add_action('admin_menu', 'visitor_notification_add_admin_menu');
function visitor_notification_add_admin_menu() {
    add_options_page('Visitor Notification Settings', 'Visitor Notification', 'manage_options', 'visitor-notification-settings', 'visitor_notification_settings_page');
}

// Register settings
add_action('admin_init', 'visitor_notification_register_settings');
add_action('admin_init', 'visitor_notification_settings_fields');