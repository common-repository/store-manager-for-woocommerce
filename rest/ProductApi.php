<?php

namespace STORE_MANAGER\Rest;

use STORE_MANAGER\App\Product\Product;
use WP_REST_Controller;

class ProductApi extends WP_REST_Controller {

    /**
     * ProductApi constructor.
     */
    public function __construct() {
        $this->namespace = Api::NAMESPACE_NAME . '/' . Api::VERSION;
        $this->rest_base = Api::PRODUCT_ROUTE_NAME;
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
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_products' ),
                    'permission_callback' => array( $this, 'products_permissions_check' ),
                    'args'                => $this->get_collection_params(),
                ),
                'schema' => array( $this, 'get_item_schema' ),
            )
        );

        register_rest_route(
            $this->namespace,
            $this->rest_base . '/(?P<id>[\d]+)',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_product' ),
                    'permission_callback' => array( $this, 'products_permissions_check' ),
                    'args'                => $this->get_collection_params(),
                ),
                array(
                    'methods'             => \WP_REST_Server::EDITABLE,
                    'callback'            => array( $this, 'update_product' ),
                    'permission_callback' => array( $this, 'products_permissions_check' ),
                    'args'                => $this->get_collection_params(),
                ),
                'schema' => array( $this, 'get_item_schema' ),
            )
        );
    }

    /**
     * Get products
     *
     * @return \WP_REST_Response
     */
    public function get_products( $request ) {

        $product_per_page = $request->get_param( 'per_page' );
        $product_category = $request->get_param( 'category' );
        $product_type = $request->get_param( 'type' );
        $paged = $request->get_param( 'page' );
		$product_name = $request->get_param( 'search' );
        $product_status = $request->get_param( 'status' );

        $response = Product::get_all_products_details( $product_per_page, $product_category, $product_type, $paged, $product_name, $product_status );

        return rest_ensure_response( $response );
    }

    /**
     * Get product
     *
     * @return \WP_REST_Response
     */
    public function get_product( $request ) {

        $product_id = $request->get_param( 'id' );

        $response = Product::get_product_details( $product_id );

        return rest_ensure_response( $response );
    }

    /**
     * Update product
     *
     * @return \WP_REST_Response
     */
    public function update_product( $request ) {

        $request_data = json_decode( $request->get_body() );

        $stock_data = [
            'manage_stock'    => sanitize_text_field( $request_data->manage_stock ),
            'stock_quantity'  => $request_data->stock_quantity,
            'stock_status'    => sanitize_text_field( $request_data->stock_status ),
            'backorders'      => sanitize_text_field( $request_data->backorders ),
        ];

        $product_id = absint( $request->get_param( 'id' ));

        $response = Product::update_stock_info( $product_id, $stock_data );

        return rest_ensure_response( $response );
    }

    /**
     * Check permissions for the posts.
     *
     * @param \WP_REST_Request $request
     *
     * @return bool|\WP_Error
     */
    public function products_permissions_check( $request ) {
        return current_user_can( 'manage_options');
    }

    /**
     * Get the query params for collections.
     *
     * @return array
     */
    public function get_collection_params() {
        return array(
            'product_category' => array(
                'description'       => __( 'Product category slug', 'store-manager-for-woocommerce' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'product_type' => array(
                'description'       => __( 'Product type', 'store-manager-for-woocommerce' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'paged' => array(
                'description'       => __( 'Current page', 'store-manager-for-woocommerce' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'per_page' => array(
                'description'       => __( 'Number of products to display per page', 'store-manager-for-woocommerce' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'search' => array(
                'description'       => __( 'Search term', 'store-manager-for-woocommerce' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'status' => array(
                'description'       => __( 'Product status', 'store-manager-for-woocommerce' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
        );
    }

    /**
     * Get the Product schema, conforming to JSON Schema.
     *
     * @return array
     */
    public function get_item_schema() {
        return array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'product',
            'type'       => 'object',
            'properties' => array(
                'id'   => array(
                    'description' => __( 'Unique identifier for the object.', 'store-manager-for-woocommerce' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'name' => array(
                    'description' => __( 'Product name.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'sku' => array(
                    'description' => __( 'Product SKU.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'regular_price' => array(
                    'description' => __( 'Product regular price.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'sale_price' => array(
                    'description' => __( 'Product sale price.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'stock_quantity' => array(
                    'description' => __( 'Product stock quantity.', 'store-manager-for-woocommerce' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'stock_status' => array(
                    'description' => __( 'Product stock status.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'backorder' => array(
                    'description' => __( 'Product backorder status.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'product_type' => array(
                    'description' => __( 'Product type.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'wc_stock' => array(
                    'description' => __( 'Product stock management.', 'store-manager-for-woocommerce' ),
                    'type'        => 'boolean',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
            ),
        );
    }
}