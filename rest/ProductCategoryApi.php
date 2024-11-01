<?php 

namespace STORE_MANAGER\Rest;

use WP_REST_Controller;
use WP_REST_Server;
use STORE_MANAGER\App\Utilities\ProductHelper;

/**
 * Class ProductCategoryApi
 * @package STORE_MANAGER\Rest
 */
class ProductCategoryApi extends WP_REST_Controller {

    /**
     * ProductCategoryApi constructor.
     */
    public function __construct() {
        $this->namespace = Api::NAMESPACE_NAME . '/' . Api::VERSION;
        $this->rest_base = Api::PRODUCT_CATEGORY_ROUTE_NAME;
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
                    'callback'            => array( $this, 'get_product_categories' ),
                    'permission_callback' => array( $this, 'product_categories_permissions_check' ),
                    'args'                => $this->get_collection_params(),
                ),
                'schema' => array( $this, 'get_item_schema' ),
            )
        );
    }

    /**
     * Get product categories
     *
     * @return \WP_REST_Response
     */
    public function get_product_categories() {
        $response = ProductHelper::get_product_categories();

        return rest_ensure_response( $response );
    }

    /**
     * Check permissions for the posts.
     *
     * @param \WP_REST_Request $request
     *
     * @return bool|\WP_Error
     */
    public function product_categories_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }

    /**
     * Get the query params for collections.
     *
     * @return array
     */
    public function get_collection_params() {
        return array();
    }

    /**
     * Get the Product schema, conforming to JSON Schema.
     *
     * @return array
     */
    public function get_item_schema() {
        return array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'product_category',
            'type'       => 'object',
            'properties' => array(
                'id'   => array(
                    'description' => __( 'Unique identifier for the object.', 'store-manager-for-woocommerce' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'name' => array(
                    'description' => __( 'Category name.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                ),
                'slug' => array(
                    'description' => __( 'Category slug.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                ),
            ),
        );
    }
}