<?php

/**
 * Ensure the file is not accessed directly.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Badge
 *
 * @package   Store Managet
 * @author    ShopManagerX
 * @link      http://domain.tld
 * @license   GPL 2.0+
 * @copyright 2024 ShopManagerX
 */

 use STORE_MANAGER\App\Badge;

 if ( !function_exists( 'store_manager_apply_product_badges' ) ) {

	/**
	 * Apply badge in a product.
	 *
	 * @param $badge Product badge
     * @param $product product object
     * 
     * @return html
	 */
	function store_manager_apply_product_badges( $badge, $product ) {

        //@TODO: Check theme compitiblity, for sticky, sidebar product. 
        if( wp_doing_ajax() || is_admin() ) {
            return;
        }

		return ( new Badge )->apply_product_badges( $badge, $product );
	}

}

if( !function_exists( 'store_manager_add_badge_to_shop_product_image' ) ){
    // Add text overlay on WooCommerce product image using woocommerce_product_get_image filter
    function store_manager_add_badge_to_shop_product_image($image, $product) {
        // Make sure product object exists
        if ( ! $product ) {
            return $image;
        }

        // Apply the badge to the product
        $badge = store_manager_apply_product_badges( $image, $product );

        if( empty( $badge ) ) {
            return $image;
        }

        return '<div style="position:relative;">' . $badge . '</div>';
    }

    add_filter('woocommerce_product_get_image', 'store_manager_add_badge_to_shop_product_image', PHP_INT_MAX, 2);
}


if( !function_exists( 'store_manager_add_badge_to_single_product_image' ) ){
    // Add text overlay on WooCommerce product image using woocommerce_single_product_image_thumbnail_html filter
    function store_manager_add_badge_to_single_product_image( $image, $attachment_id ) {
        $product = wc_get_product( get_post_parent( $attachment_id ) );
        // Make sure product object exists
        if ( ! $product ) {
            return $image;
        }

        // Apply the badge to the product
        $badge = store_manager_apply_product_badges( $image, $product );

        if( empty( $badge ) ) {
            return $image;
        }

        return $badge;
    }

    add_filter('woocommerce_single_product_image_thumbnail_html', 'store_manager_add_badge_to_single_product_image', PHP_INT_MAX, 2);
}

// add_action( 'woocommerce_before_shop_loop_item_title', 'add_custom_text_to_product_image' );
// add_filter('woocommerce_product_get_image', 'wish_me_add_icon_to_product_image', 10, 2);
// add_filter( 'woocommerce_cart_item_thumbnail', 'wish_me_add_icon_to_product_image', 10, 2 );
// add_filter('woocommerce_single_product_image_html', 'wish_me_add_icon_to_product_image', 10, 2);
// add_filter('woocommerce_single_product_image_thumbnail_html', 'store_manager_add_badge_to_product_image_single', 10, 2);
// add_filter('woocommerce_single_product_image_thumbnail_html', 'wish_me_add_icon_to_product_image', 10, 2);

/**
 * Add badge to product image in single product page in OceanWP theme
 * 
 * @param string $image
 * @param int $attachment_id
 */
if( ! function_exists( 'store_manager_add_badge_oceanwp' ) ){

    function store_manager_add_badge_oceanwp() {
        global $product;

        if ( ! $product ) {
            return;
        }

        // Apply badge
        $badge = store_manager_apply_product_badges( '', $product );

        if( empty($badge) ) {
            return;
        }

        echo '<div class="store-manager-product-badge">' . esc_html( $badge ) . '</div>';
    }
}