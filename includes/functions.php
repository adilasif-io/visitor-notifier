<?php
// Function to send email notification
function send_visitor_notification() {
    if (!is_admin()) { // Check if the request is from the frontend
        // Schedule the notification task to run asynchronously
        wp_schedule_single_event(time(), 'send_visitor_notification_event');
    }
}

// Hook into WordPress action to trigger the notification scheduling
add_action('wp_loaded', 'send_visitor_notification');

// Function to send email notification (asynchronously)
function send_visitor_notification_task() {
    // Get visitor IP address
    $visitor_ip = $_SERVER['REMOTE_ADDR'];
    
    // Initialize $geo_data with default values
    $geo_data = array(
        'country' => 'NA',
        'regionName' => 'NA',
        'city' => 'NA'
    );
    
    // Use IP Geolocation API to get additional information about the visitor
    $geo_data = wp_remote_get('http://ip-api.com/json/' . $visitor_ip);


    // Check if the request was successful
    if (!is_wp_error($geo_data) && wp_remote_retrieve_response_code($geo_data) === 200) {
        $geo_data = json_decode(wp_remote_retrieve_body($geo_data), true);
    } else {
        error_log('Failed to fetch visitor geolocation data.');
    }
        // Get more information about the visitor's session
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct Visit';
        $current_page = home_url(add_query_arg(array(),$wp->request));
        // $user_role = current_user_role(); // You need to implement this function to get the current user's role.
        $browser = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'N/A';
        $screen_resolution = isset($_COOKIE['screen_resolution']) ? $_COOKIE['screen_resolution'] : 'N/A';
        $language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'N/A';

        // Construct email content
        $subject = 'New visitor on your website';
        $message = 'A new visitor has accessed your website:\n\n';
        $message .= 'IP Address: ' . $visitor_ip . '\n';
        $message .= 'Location: ' . $geo_data['country'] . ', ' . $geo_data['regionName'] . ', ' . $geo_data['city'] . '\n';
        $message .= 'Date and Time: ' . current_time('mysql') . '\n';
        $message .= 'Referrer URL: ' . $referrer . '\n';
        $message .= 'Page visited: ' . $current_page . '\n';
        // $message .= 'User Role: ' . $user_role . '\n';
        $message .= 'Browser and OS: ' . $browser . '\n';
        $message .= 'Screen Resolution: ' . $screen_resolution . '\n';
        $message .= 'Language: ' . $language . '\n';

        // Send email to admin
        $admin_email = get_option('admin_email');
        if ($admin_email) {
            $mail_sent = wp_mail($admin_email, $subject, $message);
            if (!$mail_sent) {
                error_log('Failed to send visitor notification email.');
            }
        }
        else {
            error_log('Admin email address not configured.');
        }
        insert_visitor_notification_log($_SERVER['REMOTE_ADDR'], $geo_data['country'] . ', ' . $geo_data['regionName'] . ', ' . $geo_data['city']);
}

// Hook into the custom event to send the visitor notification asynchronously
add_action('send_visitor_notification_event', 'send_visitor_notification_task');