<?php

namespace STORE_MANAGER\App\Product;

class Product {

    /**
     * Get products count
     *
     * @return array
     */
	public static function get_all_products_details($posts_per_page = '', $product_category = '', $product_type = '', $paged = 1, $search_term = '', $product_status = 'all') {
		// Set a default value of -1 if $posts_per_page is empty
		$posts_per_page = !empty($posts_per_page) ? $posts_per_page : -1;

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => $posts_per_page,
			'post_status'    => 'publish',
			'paged'          => $paged,
			's'              => $search_term, // Add search parameter
		);

		if (!empty($product_category)) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $product_category,
				),
			);
		}

		// Exclude external products
		if ($product_status !== 'all') {
			// Exclude external products
			$args['tax_query'][] = array(
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => 'external',
				'operator' => 'NOT IN',
			);
		}

		if (!empty($product_type)) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => $product_type,
			);
		}

		// Add meta query based on product status
		if ($product_status === 'managed') {
			$args['meta_query'] = array(
				array(
					'key' => '_manage_stock',
					'value' => 'yes',
				),
			);
		} elseif ($product_status === 'low_stock') {
			$args['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key' => '_stock',
					'value' => 5,
					'compare' => '<',
					'type' => 'NUMERIC',
				),
				array(
					'key' => '_stock_status',
					'value' => 'outofstock',
					'compare' => '!=',
				),
				array(
					'key' => '_manage_stock',
					'value' => 'yes',
				),
			);
		} elseif ($product_status === 'out_of_stock') {
			$args['meta_query'] = array(
				array(
					'key' => '_stock_status',
					'value' => 'outofstock',
				),
			);
		}

    	$products_query = new \WP_Query($args);
		$products_details = array();

		while ($products_query->have_posts()) {
			$products_query->the_post();
			$product_id = get_the_ID();
			$product = wc_get_product($product_id);

			if ($product) {
				$categories = get_the_terms($product_id, 'product_cat');
				$category_name = !empty($categories) ? $categories[0]->name : '';
				$product_detail = array(
					'id'             => $product_id,
					'image_url'      => wp_get_attachment_url($product->get_image_id()),
					'name'           => $product->get_name(),
					'sku'            => $product->get_sku(),
					'regular_price'  => $product->get_regular_price(),
					'sale_price'     => $product->get_price(),
					'stock_quantity' => $product->get_stock_quantity(),
					'stock_status'   => $product->get_stock_status(),
					'product_type'   => $product->get_type(),
					'backorders'     => $product->get_backorders(),
					'wc_stock'       => $product->managing_stock(),
					'category_name'  => $category_name,
				);

				if ($product->is_type('variable')) {
					$product_detail['variations'] = array();

					$variations = $product->get_available_variations();
					foreach ($variations as $variation) {
						$variation_product = wc_get_product($variation['variation_id']);
						$variation_detail = array(
							'id'             => $variation['variation_id'],
							'image_url'      => wp_get_attachment_url($variation_product->get_image_id()),
							'name'           => implode(', ', $variation['attributes']),
							'sku'            => $variation_product->get_sku(),
							'regular_price'  => !empty($variation_product->get_regular_price()) ? $variation_product->get_regular_price() : $product->get_regular_price(),
							'sale_price'     => !empty($variation_product->get_price()) ? $variation_product->get_price() : $product->get_price(),
							'stock_quantity' => $variation_product->get_stock_quantity(),
							'stock_status'   => $variation_product->get_stock_status(),
							'product_type'   => $variation_product->get_type(),
							'backorders'     => $variation_product->get_backorders(),
							'wc_stock'       => $variation_product->managing_stock(),
							'category_name'  => $category_name,
						);
						$product_detail['variations'][] = $variation_detail;
					}
				}

				$products_details[] = $product_detail;
			}
		}

		wp_reset_postdata();

		return array(
			'products'       => $products_details,
			'max_num_pages'  => $products_query->max_num_pages,
			'total_products' => $products_query->found_posts,
		);
	}

	/**
	 * Get product details
	 *
	 * @return array
	 */
	public static function get_product_details($product_id) {
		$product = wc_get_product($product_id);
		$product_detail = array();

		if ($product) {
			$categories = get_the_terms($product_id, 'product_cat');
			$category_name = !empty($categories) ? $categories[0]->name : '';
			$product_detail = array(
				'id'             => $product_id,
				'image_url'      => wp_get_attachment_url($product->get_image_id()),
				'name'           => $product->get_name(),
				'sku'            => $product->get_sku(),
				'regular_price'  => $product->get_regular_price(),
				'sale_price'     => $product->get_price(),
				'stock_quantity' => $product->get_stock_quantity(),
				'stock_status'   => $product->get_stock_status(),
				'product_type'   => $product->get_type(),
				'backorders'     => $product->get_backorders(),
				'wc_stock'       => $product->managing_stock(),
				'category_name'  => $category_name,
			);

			if ($product->is_type('variable')) {
				$product_detail['variations'] = array();

				$variations = $product->get_available_variations();
				foreach ($variations as $variation) {
					$variation_product = wc_get_product($variation['variation_id']);
					$variation_detail = array(
						'id'             => $variation['variation_id'],
						'image_url'      => wp_get_attachment_url($variation_product->get_image_id()),
						'name'           => implode(', ', $variation['attributes']),
						'sku'            => $variation_product->get_sku(),
						'regular_price'  => !empty($variation_product->get_regular_price()) ? $variation_product->get_regular_price() : $product->get_regular_price(),
						'sale_price'     => !empty($variation_product->get_price()) ? $variation_product->get_price() : $product->get_price(),
						'stock_quantity' => $variation_product->get_stock_quantity(),
						'stock_status'   => $variation_product->get_stock_status(),
						'product_type'   => $variation_product->get_type(),
						'backorders'     => $product->get_backorders(),
						'wc_stock'       => $variation_product->managing_stock(),
						'category_name'  => $category_name,
					);
					$product_detail['variations'][] = $variation_detail;
				}
			}
		}

		wp_reset_postdata();

		return $product_detail;
	}

	/**
	 * Update product details
	 *
	 * @return array
	 */
	
	public static function update_stock_info($product_id, $stock_info) {


		if (!is_array($stock_info)) {
			return false;
		}

		$categories = get_the_terms($product_id, 'product_cat');
		$category_name = !empty($categories) ? $categories[0]->name : '';
	
		if ( ! empty( $stock_info['manage_stock'] ) ) {
			update_post_meta( $product_id, '_manage_stock', $stock_info['manage_stock'] );
		}

		if( isset($stock_info['stock_quantity']) && ! is_null($stock_info['stock_quantity'] ) ) {
			update_post_meta( $product_id, '_stock', $stock_info['stock_quantity'] );
			update_post_meta( $product_id, '_stock_status', ( $stock_info['stock_quantity'] > 0 ) ? 'instock' : 'outofstock' );
		}

		if ( ! empty( $stock_info['stock_status'] ) ) {
			update_post_meta($product_id, '_stock_status', $stock_info['stock_status']);
		}

		if( ! empty( $stock_info['backorders'] ) ) {
			update_post_meta($product_id, '_backorders', $stock_info['backorders']);
		}

		wc_delete_product_transients($product_id);

		$product = wc_get_product($product_id);
	
		// Retrieve specific product details
		$updated_product_info = array(
			'id'             => $product_id,
			'image_url'      => wp_get_attachment_url($product->get_image_id()),
			'name'           => $product->get_name(),
			'sku'            => $product->get_sku(),
			'regular_price'  => $product->get_regular_price(),
			'sale_price'     => $product->get_price(),
			'stock_quantity' => $product->get_stock_quantity(),
			'stock_status'   => $product->get_stock_status(),
			'product_type'   => $product->get_type(),
			'backorders'     => $product->get_backorders(),
			'wc_stock'       => $product->managing_stock(),
			'category_name'  => $category_name,
			'backorders'     => $product->get_backorders(),
		);
	
		return $updated_product_info;
	}

}