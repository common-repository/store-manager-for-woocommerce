<?php

namespace STORE_MANAGER\Backend;

class Enqueue {

    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'admin_script'], 10, 1);
    }

    /**
     * Enqueue styles and scripts on the admin dashboard.
     *
     * @param string $page The current admin page.
     * @return void
     */
    public function admin_script( $page ) {

        // // Enqueue the CSS file.
        // wp_enqueue_style('ascode-woo-calculator-css', STORE_MANAGER_ASSETS . '/admin/css/output.css');

        // Check if the current admin page matches your target page.
        if ( $page === 'toplevel_page_store-manager' ) {
             wp_enqueue_style('store-manager-dashboard-css', STORE_MANAGER_URL . '/backend/views/assets/tailwind.css', [], '1.0.0', 'all');

            // Enqueue the JavaScript file.
            wp_enqueue_script('store-manager-dashboard', STORE_MANAGER_ASSETS . '/build/plugin-admin.js', ['wp-element'], '1.0.0' , true);
            wp_localize_script('store-manager-dashboard',
                'SMX',
                array(
                    'rest_nonce'  => wp_create_nonce( 'wp_rest' ),
                    'rest_url' => rest_url('smx/v1'),
                    'badge_image_file' => STORE_MANAGER_URL . '/backend/views/assets/badge/badgeImageData.json',
                    'badge_image_file' => STORE_MANAGER_URL
                ));
        }
    }
}