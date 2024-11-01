<?php

namespace STORE_MANAGER\App;

class Filter {

    public function get_filters() {
        $filters = $this->get_rows();

		if ( ! is_array( $filters ) ) {
			return array();
		}

        $results = array_map(
            static function ( $filter ) {
                if ( isset( $filter->filter_data, $filter->id ) ) {
                    $data       = json_decode( $filter->filter_data, true );
                    $data       = (array) $data;
                    $data['id'] = $filter->id;

                    return  $data;
                }

                return false;
            },
            $filters
        );

        if ( is_array( $results ) ) {
            return $results;
        }

        return array();
    }

    /**
     * Save filter into database.
     *
     * @param array $config filter config.
     * @since 1.0.0
     */
	public function save_filter( $config ) {//phpcs:ignore
        if ( is_user_logged_in() ) {
            $config['created_by']  = get_current_user_id();
            $config['modified_by'] = get_current_user_id();
        }

        $config['created_date']  = gmdate( 'Y-m-d H:i:s' );
        $config['updated_date'] = gmdate( 'Y-m-d H:i:s' );
        // ==================================.
        $filter_id = $this->insert( $config );
        // ==================================.
        $filter = $this->get_row( $filter_id );

        if ( ! $filter ) {
            return new \WP_Error(
                'rest_not_added',
                __( 'Sorry, Failed to Save filter.', 'store-manager-for-woocommerce' ),
                array( 'status' => 400 )
            );
        }

        $data = array();

        if ( isset( $filter->filter_data ) && isset( $filter->id ) ) {
            $data       = (array) json_decode( $filter->filter_data, true );
            $data['id'] = absint( $filter->id );
        }

        return $data;
    }

    /**
     * Get a filter by id.
     *
     * @param int|mixed $id filter id.
     * @return \SMX\App\Utility\Config|\WP_Error
     * @since 1.0.0
     */
    public function get_filter( $id ) {
        $filter = $this->get_row( $id );

        if ( ! $filter ) {
            return new \WP_Error(
                'rest_not_found',
                __( 'Sorry, Invalid filter id.', 'store-manager-for-woocommerce' ),
                array( 'status' => 400 )
            );
        }

        if ( isset( $filter->filter_data ) ) {
            $filter       = json_decode( $filter->filter_data, true );
            $filter       = (array) $filter;
            $filter['id'] = $id;

            return  $filter ;
        }

        return new \WP_Error(
            'rest_not_found',
            __( 'Sorry, Invalid filter data.', 'store-manager-for-woocommerce' ),
            array( 'status' => 400 )
        );
    }

    /**
     * Delete filter from database.
     *
     * @param int $id Filter id.
     * @return int|bool
     * @since 1.0.0
     */
    public function delete_filter( $id ) {
        return $this->delete( $id );
    }

    /**
     * Update filter into database.
     *
     * @param int   $id     filter id.
     * @param array $config filter config.
     * @return \STORE_MANAGER\App\Utility\Config|\WP_Error
     * @since 1.0.0
     */
    public function update_filter( $id, $config ) {

        $id = absint( $id );

        if ( is_user_logged_in() ) {
            $config['modified_by'] = get_current_user_id();
        }

        $config['modified_date'] = gmdate( 'Y-m-d H:i:s' );
        // Get filter from cache or fetch from DB.
        $filter = $this->get_row( $id );

        if ( ! isset( $config['name'] ) && ! empty( $filter->name ) ) {
            $config['name'] = $filter->name;
        }

        // Update filter into database.
        $update = $this->update( $config, $id );

        if ( ! $update || ! $filter ) {
            return new \WP_Error(
                'rest_not_added',
                __( 'Sorry, the filter could not be updated.', 'store-manager-for-woocommerce' ),
                array( 'status' => 400 )
            );
        }

        $data = array();

        if ( isset( $filter->filter_data ) && isset( $filter->id ) ) {
            $data       = (array) json_decode( $filter->filter_data, true );
            $data['id'] = absint( $filter->id );
        }

        return $data;
    }

     /**
     * Delete filter from database.
     *
     * @param int $id filter id.
     * @return bool|int
     * @since 1.0.0
     */
    public function delete( $id ) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'store_manager_filters';

		return $wpdb->delete( $table_name, array( 'id' => $id ), array( '%d' ) );//phpcs:ignore
    }

    /**
     * Update filter into database.
     *
     * @param array $config filter config.
     * @param int   $id     filter id.
     * @return bool|int
     * @since 1.0.0
     */
    public function update( $config, $id ) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'store_manager_filters';

		return $wpdb->update(  //phpcs:ignore
            $table_name,
            array(
                'filter_name'   => $config['name'],
                'filter_data'   => wp_json_encode($config),
                'created_by'    => $config['created_by'],
                'created_date'  => $config['created_date'],
                'updated_date'  => $config['updated_date']
            ),
            array( 'id' => $id ),
            array(
                '%s', // filter_name
                '%s', // filter_data
                '%s', // created_by
                '%s', // created_date
                '%s'  // updated_date
            ),
            array( '%d' )
        );
    }

    /**
     * Insert filter into database.
     *
     * @param array $config filter config.
     * @return int
     * @since 1.0.0
     */
    public function insert( $config ) {
        global $wpdb;

		$insert = $wpdb->insert( // phpcs:ignore
            "{$wpdb->prefix}store_manager_filters",
            array(
                'filter_name'   => $config['name'],
                'filter_data'   => wp_json_encode($config),
                'created_by'    => $config['created_by'],
                'created_date'  => $config['created_date'],
                'updated_date'  => $config['updated_date']
            ),
            array(
                '%s', // filter_name
                '%s', // filter_data
                '%s', // created_by
                '%s', // created_date
                '%s'  // updated_date
            )
        );        

        if ( $insert ) {
            return $wpdb->insert_id;
        }

        return 0;
    }

    /**
     * Get all rows from database.
     *
     * @noinspection SqlResolve
     * @return array|null
     * @since        1.0.0
     */
    public function get_rows() {
        global $wpdb;
        // Get all rows
		$get_filters = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}store_manager_filters", OBJECT ); //phpcs:ignore
        $filters     = array();

        foreach ( $get_filters as $result ) {
            $filters[ $result->id ] = $result;
        }

        return $filters;
    }

     /**
     * Single Row Query by id.
     *
     * @param int $id Filter id.
     * @return object|null
     * @since        1.0.0
     */
    public function get_row( $id ) {
        global $wpdb;
        // Prepare and execute the query in one step.
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}store_manager_filters WHERE id = %d", $id ), OBJECT );
    }    
}