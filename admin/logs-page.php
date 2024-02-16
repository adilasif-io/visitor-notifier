<?php
// Function to display visitor notification logs
function display_visitor_notification_logs() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'visitor_notification_logs';

    $logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date_time DESC", ARRAY_A);

    echo '<h2>Visitor Notification Logs</h2>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>IP Address</th><th>Location</th><th>Date & Time</th></tr></thead>';
    echo '<tbody>';
    foreach ($logs as $log) {
        echo '<tr>';
        echo '<td>' . $log['id'] . '</td>';
        echo '<td>' . $log['ip_address'] . '</td>';
        echo '<td>' . $log['location'] . '</td>';
        echo '<td>' . $log['date_time'] . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

// Add logs page to admin menu
add_action('admin_menu', 'visitor_notification_add_log_menu');
function visitor_notification_add_log_menu() {
    add_options_page('Visitor Notification Logs', 'Visitor Notification Logs', 'manage_options', 'visitor-notification-logs', 'display_visitor_notification_logs');
}