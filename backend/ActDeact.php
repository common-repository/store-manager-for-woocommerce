<?php

namespace STORE_MANAGER\Backend;

/**
 * Activate and deactivate method of the plugin and relates.
 */
class ActDeact {
    
    /**
     * Constructor for the class
     */
    public function __construct() { 
        self::create_filter_table(); 
        self::create_badge_table();
        self::update_badge_table();
    } 

    /**
     * Method to create database table
     * 
     * @return void
     */
    protected static function create_badge_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'store_manager_badges';
    
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            badge_name varchar(255) NOT NULL,
            badge_type varchar(100) NOT NULL,
            filter text NOT NULL,
            badge_style text NOT NULL,
            priority tinyint(3) NOT NULL DEFAULT 1,
            status tinyint(3) NOT NULL DEFAULT 1,
            created_by mediumint(9) NOT NULL,
            valid_from datetime DEFAULT null,
            valid_to datetime DEFAULT null,
            created_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_date datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
    
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
    } 
    
    /**
     * Method to create database table
     * 
     * @return void
     */
    protected static function create_filter_table() { 
        global $wpdb;

        $table_name = $wpdb->prefix . 'store_manager_filters';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            filter_name varchar(255) NOT NULL,
            filter_data text NOT NULL,
            created_by bigint(20) NOT NULL,
            created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
        dbDelta( $sql );
    }

    /**
     * Method to update the badge table if a new version of the plugin is activated or the plugin is updated
     * 
     * @return void
     */
    public static function update_badge_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'store_manager_badges';

        // Check if the table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
            // Check if the 'badge_settings' column exists
            $column_exists = $wpdb->get_results( "SHOW COLUMNS FROM `$table_name` LIKE 'badge_settings'" );
            
            // If the column does not exist, alter the table to add it
            if (empty($column_exists)) {
                $wpdb->query("ALTER TABLE $table_name ADD badge_settings text DEFAULT NULL;");
            }
        }
    }

    /** 
     * Method to check if the plugin needs to be updated
     * 
     * @return void
    */
    public static function plugin_check_update() {
        $current_version = STORE_MANAGER_VERSION; // Replace with your plugin's current version
        $installed_version = get_option('store_manager_version');
    
        // If the version is not set or different, run the update logic
        if ($installed_version != $current_version) {
            self::update_badge_table(); // Add badge_settings column if not exists
            
            // Update the version in the options table
            update_option('store_manager_version', $current_version);
        }
    }
}