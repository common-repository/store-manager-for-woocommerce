<?php 

namespace STORE_MANAGER\App\Utilities;

class ProductHelper {

    /**
     * Get products count
     *
     * @return array
     */
    public static function get_products_count() {
        $products_count = wp_count_posts('product');
        $managed_products = 0;
        $low_stock_products = 0;
        $out_of_stock_products = 0;
    
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        );
    
        $products_query = new \WP_Query($args);
    
        while ($products_query->have_posts()) {
            $products_query->the_post();
            $product_id = get_the_ID();
            $product = wc_get_product($product_id);
    
            if ($product->managing_stock()) { // check if stock management is enabled
                $managed_products++;
    
                if ($product->get_stock_quantity() > 0 && $product->get_stock_quantity() < 5) {
                    $low_stock_products++;
                }
            }
    
            if ($product->get_stock_status() === 'outofstock') {
                $out_of_stock_products++;
            }
        }
    
        wp_reset_postdata();
    
        $response = array(
            'total_products'       => $products_count->publish,
            'managed_products'     => $managed_products,
            'low_stock_products'   => $low_stock_products,
            'out_of_stock_products' => $out_of_stock_products,
        );
    
        return $response;
    }

    /**
     * Get product categories
     *
     * @return array
     */
    public static function get_product_categories() {
        $product_categories = get_terms(array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ));
    
        $categories = array();
    
        foreach ($product_categories as $product_category) {
            $parent_category = $product_category->parent ? get_term($product_category->parent, 'product_cat') : false;
            $category_name = $parent_category ? $parent_category->name . ' > ' . $product_category->name : $product_category->name;
    
            $categories[] = array(
                'id'   => $product_category->term_id,
                'name' => $category_name,
                'slug' => $product_category->slug,
            );
        }
    
        return $categories;
    }

    /**
     * Get product types
     *
     * @return array
     */
    public static function get_product_types() {
        $product_types = wc_get_product_types();

        return $product_types;
    }
}  