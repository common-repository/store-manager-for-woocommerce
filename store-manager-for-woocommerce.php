<?php

/** 
 * Plugin Name:       Store Manager for WooCommerce - Product Badge and Stock Management
 * Plugin URI:        https://shopmanagerx.wordpress.com/
 * Description:       The Ultimate WooCommerce Store Management Plugin for Stock and Badge Control.
 * Version:           1.1.4
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Shop ManagerX
 * Author URI:        https://shopmanagerx.wordpress.com/
 * License:           GPLv3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       store-manager-for-woocommerce
 * Domain Path:       /languages
 * * Requires Plugins: woocommerce
 * @package     Store Manager For WooCommerce
 * @copyright   Copyright (C) 2023 Shop Manager X. All rights reserved.
 * @license     GPLv3 or later
 * @since       1.0.0
 */

// Ensure the file is not accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Check if the Composer autoload file exists, and if not, show an error message.
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    die('Please run `composer install` in the main plugin directory.');
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Plugin main class
 */
final class Store_Manager {

    /**
     * Define plugin version
     * 
     * @var string
     */
    const store_manager_version = '1.1.4';

    // Private constructor to enforce singleton pattern.
    private function __construct()
    {
        $this->define_constants();

        // Register activation hook.
        register_activation_hook(__FILE__, [$this, 'activate']);

        // Hook into the upgrader process to handle plugin updates
        add_action('upgrader_process_complete', array($this, 'update'), 10, 2);

        // Hook into the 'plugins_loaded' action to initialize the plugin.
        add_action('plugins_loaded', [$this, 'init_plugin']);
    }

    /**
     * Singleton instance
     *
     * @return store_manager
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define constants for the plugin.
     *
     * @return void
     */
    function define_constants() {
        define('STORE_MANAGER_VERSION', self::store_manager_version);
        define('STORE_MANAGER_FILE', __FILE__);
        define('STORE_MANAGER_DIR_PATH', plugin_dir_path(STORE_MANAGER_FILE));
        define('STORE_MANAGER_URL', plugin_dir_url(STORE_MANAGER_FILE));
        define('STORE_MANAGER_ASSETS', STORE_MANAGER_URL . 'assets');
        define('STORE_MANAGER_BACKEND_ASSETS', STORE_MANAGER_URL . 'assets');
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    function activate()
    {
        update_option('store_manager_version', STORE_MANAGER_VERSION);
        // Set an option to store the installation time.
        $installed = get_option('shop_manager_install_time');

        if (!$installed) {
            update_option('store_manager_install_time', time());
        }

        new STORE_MANAGER\Backend\ActDeact();
    }

    /**
     * Update method to be called on plugin update
     * 
     * @param $upgrader_object
     * @param $options
     * 
     * @return void
     */
    public function update($upgrader_object, $options) {
        if ($options['action'] == 'update' && $options['type'] == 'plugin') {
            // Check if plugin is being updated
            $our_plugin = plugin_basename(__FILE__);
            if (isset($options['plugins']) && in_array($our_plugin, $options['plugins'])) {
                new \STORE_MANAGER\Backend\ActDeact();
            }
        }
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        $badge_base_dir = ABSPATH . 'wp-content/plugins/store-manager-for-woocommerce/backend/views/assets/badge/badge-images';
        $badge_json_file = ABSPATH . 'wp-content/plugins/store-manager-for-woocommerce/backend/views/assets/badge/badgeImageData.json';
        if (is_admin()) {
            new STORE_MANAGER\Backend\Menu();
            // new STORE_MANAGER\App\Image\Image( $badge_base_dir, $badge_json_file);

            //Check plugin update and change the required need.
            STORE_MANAGER\Backend\ActDeact::plugin_check_update();
        }

        new STORE_MANAGER\Rest\Api();
        new STORE_MANAGER\Backend\Enqueue();
    }
}

/**
 * Initialize the main plugin.
 *
 * @return shop_manager
 */
function store_manager()
{
    return Store_Manager::init();
}

// Kick-off the plugin.
store_manager();
