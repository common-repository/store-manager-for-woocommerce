<?php

namespace STORE_MANAGER\Rest;

use WP_REST_Controller;
use WP_REST_Server;
use STORE_MANAGER\App\Utilities\ProductHelper;

/**
 * Class ProductTypeApi
 * @package STORE_MANAGER\Rest
 */
class ProductTypeApi extends WP_REST_Controller {

    /**
     * Register the routes for the objects of the controller.
     */
    public function __construct() {
        $this->namespace = Api::NAMESPACE_NAME . '/' . Api::VERSION;
        $this->rest_base = Api::PRODUCT_TYPE_ROUTE_NAME;
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            $this->rest_base,
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_product_types'),
                    'permission_callback' => array($this, 'get_product_types_permissions_check'),
                    'args'                => $this->get_collection_params(),
                ),
                'schema' => array($this, 'get_item_schema'),
            )
        );
    }

    /**
     * Get product types
     *
     * @return \WP_REST_Response
     */
    public function get_product_types() {
        $response = ProductHelper::get_product_types();

        return rest_ensure_response($response);
    }

    /**
     * Check permissions for the posts.
     *
     * @param \WP_REST_Request $request
     *
     * @return bool|\WP_Error
     */
    public function get_product_types_permissions_check() {
        return current_user_can('manage_options');
    }

    /**
     * Get the Product Type's schema, conforming to JSON Schema.
     *
     * @return array
     */
    public function get_item_schema() {
        return array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'product_type',
            'type'       => 'object',
            'properties' => array(
                'product_type' => array(
                    'description' => __('Product type.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array('view'),
                    'required'    => true,
                ),
                'slug'         => array(
                    'description' => __('An alphanumeric identifier for the resource unique to its type.', 'store-manager-for-woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view'),
                    'readonly'    => true,
                ),
            ),
        );
    }
}