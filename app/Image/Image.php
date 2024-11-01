<?php

namespace STORE_MANAGER\App\Image;

class Image {

    public function __construct($base_dir, $json_file) {
        $this->generate_badge_json($base_dir, $json_file);
    }

    /**
     * Generate a JSON file with badge image data.
     *
     * @param string $base_dir The base directory where badge images are stored.
     * @param string $json_file The path to the JSON file to be generated.
     * @return void
     */
    public function generate_badge_json($base_dir, $json_file) {
        $badges = [];
    
        // Scan through the base directory
        $categories = scandir($base_dir);
    
        foreach ($categories as $category) {
            if ($category === '.' || $category === '..') {
                continue;
            }
    
            $category_path = $base_dir . DIRECTORY_SEPARATOR . $category;
            if (is_dir($category_path)) {
                $images = scandir($category_path);
                $category_badges = [];
    
                foreach ($images as $image) {
                    if ($image === '.' || $image === '..' || !is_file($category_path . DIRECTORY_SEPARATOR . $image)) {
                        continue;
                    }
    
                    // Extract the image name without extension
                    $image_name = pathinfo($image, PATHINFO_FILENAME);
                    $image_src = 'backend/views/assets/badge/badge-images/' . str_replace($base_dir . DIRECTORY_SEPARATOR, '', $category_path) . DIRECTORY_SEPARATOR . $image;
    
                    // Add image data to the category-based array
                    $category_badges[] = [
                        'name' => $image_name,
                        'src'  => $image_src
                    ];
                }
    
                if (!empty($category_badges)) {
                    // Add the category with its badges as a flat list
                    $badges[$category] = $category_badges;
                }
            }
        }
    
        // Write only the badges data to the file
        $json_data = wp_json_encode(['badges' => $badges], JSON_PRETTY_PRINT);
        // Include WordPress file system methods
        if ( ! function_exists( 'request_filesystem_credentials' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        global $wp_filesystem;

        // Initialize the WP_Filesystem
        if ( empty( $wp_filesystem ) ) {
            // Get the file system credentials
            if ( ! WP_Filesystem() ) {
                return new \WP_Error( 'filesystem_error', __( 'Could not initialize the file system.', 'store-manager-for-woocommerce' ) );
            }
        }

        // Use WP_Filesystem methods to write the file
        if ( ! $wp_filesystem->put_contents( $json_file, $json_data, FS_CHMOD_FILE ) ) {
            return new \WP_Error( 'file_write_error', __( 'Failed to write JSON file.', 'store-manager-for-woocommerce' ) );
        }
    } 

}