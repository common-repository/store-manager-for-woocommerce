<?php //phpcs:ignore

/**
 * Search Utility
 *
 * @package    Store Manager
 * @subpackage App\Utility
 * @since      1.0.0
 * @category   Utility
 */

namespace STORE_MANAGER\App\Utilities;

use WC_Countries;
use WC_Coupon;
use WC_Data_Store;
use WP_Query;
use WP_Term_Query;
use WP_User_Query;

class Search { //phpcs:ignore

	/**
	 * Search Products by Product Title or SKU.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	public static function products( $like = '' ) {
		try {
			$data_store   = WC_Data_Store::load( 'product' );
			$get_products = $data_store->search_products( $like, 'product', true, false, 200, array(), array() ); // @phpstan-ignore-line
		} catch ( \Throwable $e ) {
			$get_products = array();
		}

		$products = array();

		if ( ! empty( $get_products ) ) {
			foreach ( $get_products as $pid ) {
				if ( ! $pid ) {
					continue;
				}

				$product = wc_get_product( $pid );

				if ( ! ( $product instanceof \WC_Product ) ) {
					continue;
				}

				$products[] = array(
					'id'    => $product->get_id(),
					'sku'   => $product->get_sku(),
					'name'  => $product->get_name(),
					'image' => wp_get_attachment_url( (int) $product->get_image_id() ),
				);
			}
		}

		return $products;
	}

	/**
	 * Search Categories by Category Name.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	public static function categories( $like = '' ) {
		$args = array(
			'taxonomy'   => 'product_cat',
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
			'number'     => 20,
		);

		if ( ! empty( $like ) ) {
			$args['search'] = $like;
		}

		$args           = (array) $args;
		$get_categories = ( new WP_Term_Query( $args ) )->get_terms();

		$categories = array();

		if ( is_array( $get_categories ) && ! empty( $get_categories ) ) {
			foreach ( $get_categories as $category ) {
				if ( ! ( $category instanceof \WP_Term ) ) {
					continue;
				}

				$categories[] = array(
					'id'   => $category->term_id,
					'name' => $category->name,
				);
			}
		}

		return $categories;
	}

	/**
	 * Search Tags by Tag Name.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	public static function tags( $like = '' ) {
		$args = array(
			'taxonomy'   => 'product_tag',
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
			'number'     => 20,
		);

		if ( ! empty( $like ) ) {
			$args['search'] = $like;
		}

		$get_tags = ( new WP_Term_Query( $args ) )->get_terms();

		$tags = array();

		if ( is_array( $get_tags ) && ! empty( $get_tags ) ) {
			foreach ( $get_tags as $tag ) {
				if ( ! ( $tag instanceof \WP_Term ) ) {
					continue;
				}

				$tags[] = array(
					'id'   => $tag->term_id,
					'name' => $tag->name,
				);
			}
		}

		return $tags;
	}

	/**
	 * Search Attributes by Attribute Name.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	public static function attributes( $like = ' ' ) {
		$attributes        = self::get_global_attributes( $like );
		$custom_attributes = self::get_custom_attributes( $like );
		return array_merge( $attributes, $custom_attributes );
	}

	/**
	 * Search Attributes by Attribute Name.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	protected static function get_global_attributes( $like ) {

		global $wpdb;
		$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name != '' AND attribute_name LIKE %s", $like . '%' );

		$global_attributes = $wpdb->get_results( $query ); //phpcs:ignore

		$result = [];

		if ( is_array( $global_attributes ) && ! empty( $global_attributes ) ) {
			foreach ( $global_attributes as $attribute ) {
				if ( ! isset( $attribute->attribute_name, $attribute->attribute_label ) ) {
					continue;
				}

				$result[] = array(
					'id'   => $attribute->attribute_name,
					'name' => $attribute->attribute_label,
				);
			}
		}

		return $result;
	}

	/**
	 * Get all product custom attributes.
	 *
	 * @param string $like Search Term.
	 * @return array
	 */
	protected static function get_custom_attributes( $like ) {
		global $wpdb;
		$query = $wpdb->prepare( 'SELECT meta.meta_id, meta.meta_key as name, meta.meta_value as type FROM ' . $wpdb->postmeta . ' AS meta, ' . $wpdb->posts . " AS posts WHERE meta.post_id = posts.id AND posts.post_type LIKE %s AND meta.meta_key='_product_attributes'", $like . '%' );

		$custom_attributes = $wpdb->get_results( $query ); //phpcs:ignore

		$result = [];

		if ( is_array( $custom_attributes ) && ! empty( $custom_attributes ) ) {
			foreach ( $custom_attributes as $value ) {
				$product_attr = maybe_unserialize( $value->type );

				if ( ! is_array( $product_attr ) ) {
					continue;
				}

				$result = array_merge( $result, self::filter_product_attributes( $product_attr, $like ) );
			}
		}

		return $result;
	}

	/**
	 * Filter Product Attributes.
	 *
	 * @param array  $product_attr Product Attributes.
	 * @param string $like         Search Term.
	 * @return array
	 */
	protected static function filter_product_attributes( $product_attr, $like ) {
		$filtered_attrs = array();

		foreach ( $product_attr as $key => $arr_value ) {
			if ( strpos( $key, 'pa_' ) !== false ) {
				continue;
			}

			if ( ! empty( $like ) && ( stripos( $arr_value['name'], $like ) === false ) ) {
				continue;
			}

			$filtered_attrs[] = array(
				'id'   => $key,
				'name' => ucwords( str_replace( '-', ' ', $arr_value['name'] ) ),
			);
		}

		return $filtered_attrs;
	}

}
