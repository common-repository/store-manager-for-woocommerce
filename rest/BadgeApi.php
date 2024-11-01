<?php

/**
 * Badge API
 *
 * @package    store-manager-for-woocommerce
 * @subpackage \Rest
 * @since      1.0.0
 * @category   Rest
 */

namespace STORE_MANAGER\Rest;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Response;
use STORE_MANAGER\App\Utilities\BadgeHelper;

class BadgeApi extends WP_REST_Controller {

    /**
     * Filter api constractor
     */
    public function __construct() {
		$this->namespace = Api::NAMESPACE_NAME . '/' . Api::VERSION;
		$this->rest_base = Api::BADGE_ROUTE_NAME;
	}

    /**
	 * Registers the routes for the objects of the controller.
	 *
	 * @return void
	 */
	public function register_routes() { //phpcs:ignore

		register_rest_route(
			Api::NAMESPACE_NAME . '/' . Api::VERSION,
			'/' . Api::BADGE_ROUTE_NAME . '/',
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
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);

		register_rest_route(
			Api::NAMESPACE_NAME . '/' . Api::VERSION,
			'/' . Api::BADGE_ROUTE_NAME . '/(?P<id>[\d]+)',
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
	 * Retrieves a list of badge items.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_REST_Response|\WP_Error
	 */
    public function get_items( $request ) {
        $results = BadgeHelper::get_badges();

        // Check if the result is an instance of WP_Error
        if ( is_wp_error( $results ) ) {
            return $results; // Return the WP_Error object as is
        }

        if( empty( $results ) ) {
            return array();
        }

        foreach( $results as $badge ) {
            $response = $this->prepare_item_for_response( $badge, $request );
            $data[]   = $this->prepare_response_for_collection( $response );

        }

        $total    = count( $results );
		$response = rest_ensure_response( $data );

		$response->header( 'X-WP-Total', (int) $total );
		$response->header( 'X-WP-TotalPages', (int) $total );

        return $response;
    }

    /**
	 * Get a item for badge.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_REST_Response|\WP_Error
	 */
    public function get_item( $request ) {
        $badge_id = $request['id'];
        $result = BadgeHelper::get_badge( $badge_id );

        // Check if the result is an instance of WP_Error
        if ( is_wp_error( $result ) ) {
            return $result; // Return the WP_Error object as is
        }

        $response = $this->prepare_item_for_response( $result, $request );

        return $response;
    }

    /**
	 * Create a item for badge.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_REST_Response|\WP_Error
	 */
    public function create_item( $request ) {
        $badge_data = $this->prepare_item_for_database( $request );
        $inserted_id = BadgeHelper::save_badge( $badge_data );
        $inserted_data = BadgeHelper::get_badge( $inserted_id );

        // Check if the result is an instance of WP_Error
        if ( is_wp_error( $inserted_data ) ) {
            return $inserted_data; // Return the WP_Error object as is
        }

        return $this->prepare_item_for_response( $inserted_data, $request );
    }

    /**
	 * Update a item for badge.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_REST_Response|\WP_Error
	 */
    public function update_item( $request ) {
        $badge_id = $request['id'];
        $update_data = $request->get_json_params();
        $update_data = $this->prepare_item_for_database($update_data);
        $updated_id = BadgeHelper::update_badge( $badge_id, $update_data );
        // Check if the result is an instance of WP_Error
        if ( is_wp_error( $updated_id ) ) {
            return $updated_id; // Return the WP_Error object as is
        }
        $updated_data = BadgeHelper::get_badge( $updated_id );

        return $this->prepare_item_for_response( $updated_data, $request );
    }

    /**
	 * Update a item for badge.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_REST_Response|\WP_Error
	 */
    public function delete_item( $request ) {
        $badge_id = $request['id'];
        $response = BadgeHelper::delete_badge( $badge_id );
        // Check if the result is an instance of WP_Error
        if ( is_wp_error( $response ) ) {
            return $response; // Return the WP_Error object as is
        }
        return $this->prepare_item_for_response( $response, $request );
    }

    /**
	 * Checks if a given request has access to read badge.
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
            'badge_name' => array(
                'description'       => __( 'Badge name', 'store-manager-for-woocommerce' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'badge_type' => array(
                'description'       => __( 'Badge type', 'store-manager-for-woocommerce' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'filter' => array(
                'description'       => __( 'Filter data', 'store-manager-for-woocommerce' ),
                'type'              => array( 'array', 'string', 'integer' ),
                'sanitize_callback' => [$this, 'custom_sanitize_filter_data'],
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'badge_style' => array(
                'description'       => __( 'Badge Style data', 'store-manager-for-woocommerce' ),
                'type'              => 'string',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'badge_settings' => array(
                'description'       => __( 'Badge settings', 'store-manager-for-woocommerce' ),
                'type'              => 'obejct',
                'serialize_callback' => 'maybe_serialize',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'priority' => array(
                'description'       => __( 'Badge priority', 'store-manager-for-woocommerce' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'status' => array(
                'description'       => __( 'Badge status', 'store-manager-for-woocommerce' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'valid_from' => array(
                'description'       => __( 'Badge valid from date', 'store-manager-for-woocommerce' ),
                'type'              => 'string',
                'format'            => 'date',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'valid_to' => array(
                'description'       => __( 'Badge valid to date', 'store-manager-for-woocommerce' ),
                'type'              => 'string',
                'format'            => 'date',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
        );
    } 
    
    /**
     * Custom sanitize filter data 
     * Validate if the data types can be different types 
     * 
     * @param $value, $request, $param
     * 
     * @return string|int|array
     */
    public function custom_sanitize_filter_data( $value, $request, $param ) {
        if ( is_string( $value ) ) {
            return sanitize_text_field( $value );
        }
        // If the value is an integer, return the integer directly
        elseif ( is_int( $value ) ) {
            return intval( $value );
        }
        // For other types, just return the value as is (or null for safety)
        return $value;
    }
    

    /**
     * Get the Product schema, conforming to JSON Schema.
     *
     * @return array
     */
    public function get_item_schema() {
        return array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'badge',
            'type'       => 'object',
            'properties' => array(
                'id'   => array(
                    'description' => __( 'Unique identifier for the object.', 'store-manager-for-woocommerce' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'name' => array(
                    'description' => __( 'Badge name.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'badge_type' => array(
                    'description' => __( 'Badge type.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'badge_style' => array(
                    'description' => __( 'Badge Style.', 'store-manager-for-woocommerce' ),
                    'type'        => 'string',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'badge_settings' => array(
                    'description' => __( 'Badge settings.', 'store-manager-for-woocommerce' ),
                    'type'        => 'object',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'priority' => array(
                    'description' => __( 'Badge priority.', 'store-manager-for-woocommerce' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'status' => array(
                    'description' => __( 'Badge status.', 'store-manager-for-woocommerce' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'filter' => array(
                    'description' => __( 'Filter.', 'store-manager-for-woocommerce' ),
                    'type'        => 'array',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'valid_from' => array(
                    'description' => __( 'Badge valid from.', 'store-manager-for-woocommerce' ),
                    'type'        => 'date',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'valid_to' => array(
                    'description' => __( 'Badge valid to.', 'store-manager-for-woocommerce' ),
                    'type'        => 'date',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'created_by' => array(
                    'description' => __( 'Badge created by.', 'store-manager-for-woocommerce' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'created_date' => array(
                    'description' => __( 'Badge created date.', 'store-manager-for-woocommerce' ),
                    'type'        => 'date',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'updated_date' => array(
                    'description' => __( 'Badge update date.', 'store-manager-for-woocommerce' ),
                    'type'        => 'date',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
            ),
        );
    }

    /**
	 * Prepares links for the request.
	 *
	 * @param object $item Item object.
	 * @return array Links for the given post.
	 */
	protected function prepare_links( $item ) {
		$id = 0;

		if ( isset( $item['id'] ) ) {
			$id = $item['id'];
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
	 * Prepares a response for insertion into a collection.
	 *
	 * @since 4.7.0
	 *
	 * @param WP_REST_Response $response Response object.
	 * @return array|mixed Response data, ready for insertion into collection data.
	 */
	public function prepare_response_for_collection( $response ) {
		if ( ! ( $response instanceof WP_REST_Response ) ) {
			return $response;
		}

		$data   = (array) $response->get_data();
		$server = rest_get_server();
		$links  = $server::get_compact_response_links( $response );

		if ( ! empty( $links ) ) {
			$data['_links'] = $links;
		}

		return $data;
	}

    /**
	 * Prepares item for database.
	 *
	 * @param object $item Item object.
	 * @return array Links for the given post.
	 */
    protected function prepare_item_for_database( $request ) {
        $prepared_data = array();
    
        // Validate and sanitize the badge_name
        if ( isset( $request['badge_name'] ) ) {
            $prepared_data['badge_name'] = sanitize_text_field( $request['badge_name'] );
        }
    
        // Validate and sanitize the badge_type
        if ( isset( $request['badge_type'] ) ) {
            $prepared_data['badge_type'] = sanitize_text_field( $request['badge_type'] );
        }
    
        // Validate and sanitize the priority
        if ( isset( $request['priority'] ) && is_numeric( $request['priority'] ) ) {
            $prepared_data['priority'] = absint( $request['priority'] );
        }
    
        // Validate and sanitize the status
        if ( isset( $request['status'] ) && is_numeric( $request['status'] ) ) {
            $prepared_data['status'] = absint( $request['status'] );
        }
    
        // Validate and sanitize the filter
        if ( isset( $request['filter'] ) ) {
            $prepared_data['filter'] = maybe_serialize( $request['filter'] );
        }

        // Validate and format valid_from date
        if( isset( $request['valid_from'] ) ) {
            $prepared_data['valid_from'] = $request['valid_from'] ? gmdate( 'Y-m-d H:i:s', strtotime( $request['valid_from'] ) ) : '';
        }
    
        // Validate and format valid_to date
        if( isset( $request['valid_to'] ) ) {
            $prepared_data['valid_to'] = $request['valid_to'] ? gmdate( 'Y-m-d H:i:s', strtotime( $request['valid_to'] ) ) : '';
        }
    
        // Badge style (assuming it's an array and already sanitized elsewhere)
        if ( isset( $request['badge_style'] ) ) {
            $prepared_data['badge_style'] = maybe_serialize( $request['badge_style'] );
        }

        if( isset( $request['badge_settings'] ) ) {
            $prepared_data['badge_settings'] = maybe_serialize( $request['badge_settings'] );
        }
    
        return $prepared_data;
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

		$data['badge_name'] = '';

		if ( ! empty( $item['badge_name'] ) ) {
			$data['badge_name'] = $item['badge_name'];
		}

        $data['badge_type'] = '';

		if ( ! empty( $item['badge_type'] ) ) {
			$data['badge_type'] = $item['badge_type'];
		}

		$data['filter'] = array();

		if ( ! empty( $item['filter'] ) ) {
			$data['filter'] = $item['filter'];
		}

		$data['badge_style'] = array();

		if ( ! empty( $item['badge_style'] ) ) {
			$data['badge_style'] = $item['badge_style'];
		}

        $data['badge_settings'] = array();
        if( !empty( $item['badge_settings'] ) ) {
            $data['badge_settings'] = maybe_unserialize( $item['badge_settings'] );
        }

		$data['priority'] = '';

		if ( ! empty( $item['priority'] ) ) {
			$data['priority'] = $item['priority'];
		}

        $data['status'] = '';

		if ( ! empty( $item['status'] ) ) {
			$data['status'] = $item['status'];
		}

        $data['created_by'] = '';

		if ( ! empty( $item['created_by'] ) ) {
			$data['created_by'] = $item['created_by'];
		}

        // Validate and format valid_from date
        if ( ! empty( $item['valid_from'] ) ) {
            $data['valid_from'] = gmdate( DATE_W3C, strtotime( $item['valid_from'] ) );
        }

        // Validate and format valid_to date
        if ( ! empty( $item['valid_to'] ) ) {
            $data['valid_to'] = gmdate( DATE_W3C, strtotime( $item['valid_to'] ));
        }

		$data['created_at'] = '';

		if ( ! empty( $item['created_date'] ) ) {
			$created_date = $item['created_date'];

			$data['created_at'] = $created_date;
		}

		$data['updated_at'] = '';

		if ( ! empty( $item['updated_date'] ) ) {
			$modified_date =  $item['updated_date'];

			$data['updated_at'] = $modified_date;
		}

		$context = ! empty( $request['context'] ) && is_string( $request['context'] ) ? $request['context'] : 'view';//phpcs:ignore

		$data = $this->filter_response_by_context( $data, $context );

		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $item ) );

		return $response;
	}
}