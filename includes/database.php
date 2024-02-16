<?php
// Function to create the visitor notification logs table
function create_visitor_notification_logs_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'visitor_notification_logs';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ip_address varchar(100) NOT NULL,
        location varchar(255) NOT NULL,
        date_time datetime NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Function to insert a log entry for visit notification
function insert_visitor_notification_log($ip_address, $location) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'visitor_notification_logs';

    $wpdb->insert(
        $table_name,
        array(
            'ip_address' => $ip_address,
            'location' => $location,
            'date_time' => current_time('mysql')
        )
    );
}