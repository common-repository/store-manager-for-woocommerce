<?php

/**
 * DropDown API
 *
 * @package    store-manager-for-woocommerce
 * @subpackage \Rest
 * @since      1.0.0
 * @category   Rest
 */

namespace STORE_MANAGER\Rest;

use STORE_MANAGER\App\Utilities\DropDown;
use WP_REST_Controller;
use WP_REST_Server;

class DropDownApi extends WP_REST_Controller {

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			Api::NAMESPACE_NAME . '/' . Api::VERSION,
			'/' . Api::DROPDOWN_ROUTE_NAME . '/',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);
	}

	/**
	 * Checks if a given request has access to read campaigns.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return bool
	 */
	public function permissions_check( $request ) { // phpcs:ignore
		return current_user_can( 'manage_options' );
	}

	/**
	 * Search a list of product items.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function get_items( $request ) {
		error_log(print_r($request['search'],true));
		$search = '';

		if ( is_string( $request['search'] ) ) {
			$search = sanitize_text_field( $request['search'] );
		}

		$dropdown_methods = array(
			'conditions'       => 'Conditions',
			'products'         => 'Products',
			'filters'          => 'Filters',
		);

		if ( ! method_exists( 'STORE_MANAGER\App\Utilities\DropDown', $search ) ) {
			return new \WP_Error(
				'rest_dropdown_invalid_search_term',
				/* translators: %s: List of valid dropdown values. */
				esc_html( sprintf( __( 'Sorry, Invalid dropdown list. Search term should be any one of these values [ %s ].', 'store-manager-for-woocommerce' ), implode( ', ', array_keys( $dropdown_methods ) ) ) ),
				array( 'status' => 404 )
			);
		}

		$result = array(
			'name'   => $search,
			'values' => DropDown::$search(),
		);

		$response = $this->prepare_item_for_response( $result, $request );
		$data     = $this->prepare_response_for_collection( $response );

		$total    = count( $result['values'] );
		$response = rest_ensure_response( $data );

		$response->header( 'X-WP-Total', (int) $total );
		$response->header( 'X-WP-TotalPages', (int) $total );

		return $response;
	}

	/**
	 * Prepares the item for the REST response.
	 *
	 * @param array            $item WordPress's representation of the item.
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function prepare_item_for_response( $item, $request ) {
		$data   = array();
		$fields = $this->get_fields_for_response( $request );

		if ( in_array( 'name', $fields, true ) && isset( $item['name'] ) ) {
			$data['name'] = $item['name'];
		}

		if ( in_array( 'values', $fields, true ) && isset( $item['values'] ) ) {
			$data['values'] = $item['values'];
		}

		$data = $this->filter_response_by_context( $data, 'view' );

		return rest_ensure_response( $data );
	}

	/**
	 * Retrieves the campaign schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'dropdown',
			'type'       => 'object',
			'properties' => array(
				'name'   => array(
					'description' => __( 'Name for the object.', 'store-manager-for-woocommerce' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'values' => array(
					'description' => __( 'Values of the object.', 'store-manager-for-woocommerce' ),
					'type'        => 'array',
					'context'     => array( 'view' ),
					'readonly'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			),
		);

		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}

	/**
	 * Retrieves the query params for collections.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		unset( $params['page'], $params['per_page'] );

		return $params;
	}

}
