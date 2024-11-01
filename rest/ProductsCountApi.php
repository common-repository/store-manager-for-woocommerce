<?php

namespace STORE_MANAGER\Rest;

use WP_REST_Controller;
use WP_REST_Server;
use STORE_MANAGER\App\Utilities\ProductHelper;

/**
 * Class ProductsCountApi
 * @package STORE_MANAGER\Rest
 */
class ProductsCountApi extends WP_REST_Controller {

    /**
     * ProductsCountApi constructor.
     */
    public function __construct() {
        $this->namespace = Api::NAMESPACE_NAME . '/' . Api::VERSION;
        $this->rest_base = Api::PRODUCTS_COUNT_ROUTE_NAME;
    }

    /**
     * Register the routes for the objects of the controller.
     *
     * @return void
     */
    public function register_route() {
        register_rest_route(
            $this->namespace,
            $this->rest_base,
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_products_count' ),
                    'permission_callback' => array( $this, 'products_count_permissions_check' ),
                    'args'                => $this->get_collection_params(),
                ),
                'schema' => array( $this, 'get_item_schema' ),
            )
        );
    }

    /**
     * Get products count
     *
     * @return WP_REST_Response
     */
    public function get_products_count() {
        $response = ProductHelper::get_products_count();

        return rest_ensure_response( $response );
    }

    /**
     * Check permissions for the posts.
     *
     * @param WP_REST_Request $request
     *
     * @return bool|WP_Error
     */
    public function products_count_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }

    /**
     * Get the Product schema, conforming to JSON Schema.
     *
     * @return array
     */
    public function get_item_schema() {
        return array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'products_count',
            'type'       => 'object',
            'properties' => array(
                'total_products' => array(
                    'description' => __( 'The number of products', 'store-manager-for-woocommerce' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                ),
                'managed_products' => array(
                    'description' => __( 'The number of managed products', 'store-manager-for-woocommerce' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                ),
                'low_stock_products' => array(
                    'description' => __( 'The number of low stock products', 'store-manager-for-woocommerce' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                ),
                'out_of_stock_products' => array(
                    'description' => __( 'The number of out of stock products', 'store-manager-for-woocommerce' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                ),
            ),
        );
    }
}