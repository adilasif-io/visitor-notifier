<?php
/**
 * Plugin Name: Visitor Notifier
 * Version: 1.0.0
 * Plugin URI: http://adilasif.io/
 * Description: Sends email notification to admin whenever someone visits the website.
 * Author: Adil Asif
 * Author URI: http://adilasif.io/
 * Requires at least: 4.0
 * Tested up to: 6.4.3
 *
 * @package WordPress
 * @author Adil Asif
 * @since 1.0.0
 */


// Include necessary files
require_once(plugin_dir_path(__FILE__) . 'admin/settings-page.php');
require_once(plugin_dir_path(__FILE__) . 'admin/logs-page.php');
require_once(plugin_dir_path(__FILE__) . 'includes/functions.php');
require_once(plugin_dir_path(__FILE__) . 'includes/database.php');

// Hook into activation to create database table
register_activation_hook(__FILE__, 'create_visitor_notification_logs_table');