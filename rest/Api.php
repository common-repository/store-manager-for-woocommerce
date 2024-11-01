<?php

namespace STORE_MANAGER\Rest;

/**
 * Class Api
 *
 * @package Woo_Manager_X\Rest
 */
Class Api {

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_rest_api' ) );
    }

    public const NAMESPACE_NAME      = 'smx';
	public const VERSION             = 'v1';
	public const PRODUCTS_COUNT_ROUTE_NAME = 'product-count';
	public const PRODUCT_CATEGORY_ROUTE_NAME = 'product-category';
	public const PRODUCT_TYPE_ROUTE_NAME = 'product-type';
	public const PRODUCT_ROUTE_NAME = 'product';
	public const SEARCH_ROUTE_NAME = 'search';
	public const DROPDOWN_ROUTE_NAME = 'dropdown';
	public const FILTER_ROUTE_NAME = 'filters';
	public const BADGE_ROUTE_NAME = 'badges';

    /**
	 * Register REST API
	 *
	 * @return void
	 */
	public function register_rest_api() {

		//wp-json/smx/v1/product-count
		$products_count = new ProductsCountApi;
		$products_count->register_route();

		//wp-json/smx/v1/product-category
		$product_category = new ProductCategoryApi;
		$product_category->register_route();

		//wp-json/smx/v1/product-type
		$product_type = new ProductTypeApi;
		$product_type->register_routes();

		//wp-json/smx/v1/product?per_page=20&category=slug&type=type&page=1&search=product_name&status=all/managed/out_of_stock/low_stock
		//wp-json/smx/v1/product/product_id?manage_stock=yes/no&stock_quantity=20&stock_status=instock/outofstock/onbackorder&backorders=yes/no/notify
		$product = new ProductApi;
		$product->register_route();

		//wp-json/smx/v1/search/product?search=product_name
		//wp-json/smx/v1/search/category?search=category_name
		//wp-json/smx/v1/search/search/tag?search=tag_name
		//wp-json/smx/v1/search/attribute?search=attribute_name
		$search = new SrearchApi();
		$search->register_routes();

		//wp-json/smx/v1/dropdown/?search=conditions
		//wp-json/smx/v1/dropdown/?search=filters
		//wp-json/smx/v1/dropdown/?search=products
		$dropdown = new DropDownApi();
		$dropdown->register_routes();

		//wp-json/smx/v1/filters/43
		//wp-json/smx/v1/filters
		$filter = new FilterApi();
		$filter->register_routes();

		//wp-json/smx/v1/badges
		$badge = new BadgeApi();
		$badge->register_routes();
	}
}