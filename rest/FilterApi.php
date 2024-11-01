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

use Error;
use STORE_MANAGER\App\Filter;
use WP_REST_Controller;
use WP_REST_Server;

class FilterApi extends WP_REST_Controller {

    /**
	 * FilterApi constructor.
	 */
	public function __construct() {
		$this->namespace = Api::NAMESPACE_NAME . '/' . Api::VERSION;
		$this->rest_base = Api::FILTER_ROUTE_NAME;
	}

    /**
	 * Registers the routes for the objects of the controller.
	 *
	 * @return void
	 */
	public function register_routes() { //phpcs:ignore

		register_rest_route(
			Api::NAMESPACE_NAME . '/' . Api::VERSION,
			'/' . Api::FILTER_ROUTE_NAME . '/',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema(),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);

		register_rest_route(
			Api::NAMESPACE_NAME . '/' . Api::VERSION,
			'/' . Api::FILTER_ROUTE_NAME . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the object.', 'store-manager-for-woocommerce' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'permissions_check' ),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);
	}

	/**
	 * Creates one item from the collection.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function create_item( $request ) {
		$config = (array) $this->prepare_item_for_database( $request );

		if ( empty( $config ) ) {
			return new \WP_Error(
				'rest_not_added',
				__( 'Sorry, the filter could not be created with empty value.', 'store-manager-for-woocommerce' ),
				array( 'status' => 400 )
			);
		}

		$config   = (array) $config;
		$filter = ( new Filter )->save_filter( $config );

		if ( is_wp_error( $filter ) ) {
			$filter->add_data( array( 'status' => 400 ) );

			return $filter;
		}

		$response = $this->prepare_item_for_response( $filter, $request );

		return $response;
	}

	/**
	 * Retrieves a list of filter items.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function get_items( $request ) {
		$params = $this->get_collection_params();
		$args   = array_intersect_key( $request->get_params(), $params );

		// unset others.
		unset( $args['per_page'], $args['page'] );

		$data      = array();
		$filters = ( new Filter )->get_filters();

		if ( empty( $filters ) ) {
			return new \WP_Error(
				'rest_filter_not_available',
				__( 'No filter available. Create a filter first', 'store-manager-for-woocommerce' ),
				array( 'status' => 404 )
			);
		}

		foreach ( $filters as $filter ) {
			$response = $this->prepare_item_for_response( $filter, $request );
			$data[]   = $this->prepare_response_for_collection( $response );
		}

		$total    = count( $filters );
		$response = rest_ensure_response( $data );

		$response->header( 'X-WP-Total', (int) $total );
		$response->header( 'X-WP-TotalPages', (int) $total );

		return $response;
	}

	/**
	 * Updates one item from the collection.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function update_item( $request ) {
		$filter = ( new Filter )->get_filter( absint( $request['id'] ) );

		if ( is_wp_error( $filter ) ) {
			return $filter;
		}

		$prepared = (array) $this->prepare_item_for_database( $request );


		$updated = ( new Filter )->update_filter( absint( $request['id'] ), $prepared );

		error_log(print_r($updated, true));

		if ( ! $updated ) {
			return new \WP_Error(
				'rest_not_updated',
				__( 'Sorry, the filter could not be updated.', 'store-manager-for-woocommerce' ),
				array( 'status' => 400 )
			);
		}

		$filter = ( new Filter )->get_filter( absint( $request['id'] ) );

		$response = $this->prepare_item_for_response( $filter, $request );

		return rest_ensure_response( $response );
	}
	

	/**
	 * Retrieves one item from the collection.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function get_item( $request ) {
		$filter= ( new Filter )->get_filter( absint( $request['id'] ) );

		if ( is_wp_error( $filter ) ) {
			return $filter;
		}

		$response = $this->prepare_item_for_response( $filter, $request );

		return rest_ensure_response( $response );
	}

	/**
	 * Deletes one item from the collection.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function delete_item( $request ) {
		$filter = ( new Filter )->get_filter( absint( $request['id'] ) );

		if ( is_wp_error( $filter ) ) {
			return $filter;
		}

		$previous = $this->prepare_item_for_response( $filter, $request );

		$deleted = ( new Filter )->delete_filter( absint( $request['id'] ) );

		if ( ! $deleted ) {
			return new \WP_Error(
				'rest_not_deleted',
				__( 'Sorry, the filter could not be deleted.', 'store-manager-for-woocommerce' ),
				array( 'status' => 400 )
			);
		}

		$data = array(
			'deleted'  => true,
			'previous' => $previous->get_data(),
		);

		return rest_ensure_response( $data );
	}


    /**
	 * Checks if a given request has access to read filters.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return bool|\WP_Error
	 */
	public function permissions_check( $request ) { //phpcs:ignore
		$permission = current_user_can( 'manage_options' );

		if ( ! $permission ) {
			return new \WP_Error(
				'rest_not_found',
				__( 'Sorry, Permission Denied.', 'store-manager-for-woocommerce' ),
				array( 'status' => 400 )
			);
		}

		return $permission;
	}

	/**
	 * Prepares one item for create or update operation.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return object
	 */
	protected function prepare_item_for_database( $request ) {
		$valid_keys = array(
			'name',
			'products',
			'conditions'
		);

		$prepared = array();

		foreach ( $valid_keys as $key ) {
			if ( ! isset( $request[ $key ] ) ) {
				continue;
			}

			$prepared[ $key ] = $request[ $key ];
		}

		return (object) $prepared;
	}

	/**
	 * Prepares links for the request.
	 *
	 * @param object $item Item object.
	 * @return array Links for the given post.
	 */
	protected function prepare_links( $item ) {
		$id = 0;

		if ( isset( $item->id ) ) {
			$id = $item->id;
		}

		$base = sprintf( '%s/%s', $this->namespace, $this->rest_base );

		return array(
			'self'       => array(
				'href' => rest_url( trailingslashit( $base ) . $id ),
			),
			'collection' => array(
				'href' => rest_url( $base ),
			),
		);
	}

	/**
	 * Prepares the item for the REST response.
	 *
	 * @param object           $item    WordPress's representation of the item.
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function prepare_item_for_response( $item, $request ) {//phpcs:ignore
		$data       = array();

		$data['id'] = 0;

		if ( ! empty( $item['id'] ) ) {
			$data['id'] = $item['id'];
		}

		$data['name'] = '';

		if ( ! empty( $item['name'] ) ) {
			$data['name'] = $item['name'];
		}

		$data['products'] = array();

		if ( ! empty( $item['products'] ) ) {
			$data['products'] = $item['products'];
		}

		$data['conditions'] = array();

		if ( ! empty( $item['conditions'] ) ) {
			$data['conditions'] = $item['conditions'];
		}

		$data['created_by'] = '';

		if ( ! empty( $item['created_by'] ) ) {
			$data['created_by'] = $item['created_by'];
		}

		$data['created_date'] = '';

		if ( ! empty( $item['created_date'] ) ) {
			$created_date = gmdate( DATE_W3C, strtotime( $item['created_date'] ) );

			$data['created_date'] = $created_date;
		}

		$data['modified_by'] = '';

		if ( ! empty( $item['modified_by'] ) ) {
			$data['modified_by'] = $item['modified_by'];
		}

		$data['updated_date'] = '';

		if ( ! empty( $item['updated_date'] ) ) {
			$modified_date = gmdate( DATE_W3C, strtotime( $item['updated_date'] ) );

			$data['updated_date'] = $modified_date;
		}

		$context = ! empty( $request['context'] ) && is_string( $request['context'] ) ? $request['context'] : 'view';//phpcs:ignore

		$data = $this->filter_response_by_context( $data, $context );

		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $item ) );

		return $response;
	}

}