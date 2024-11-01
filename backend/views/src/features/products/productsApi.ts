import buildQueryString from '../../utils/queryBuilder';
import { apiSlice } from '../api/apiSlice';

interface ProductCount {
	total_products: string;
	managed_products: number;
	low_stock_products: number;
	out_of_stock_products: number;
}
interface ProductTypeResponseType {
	simple: string;
	grouped: string;
	external: string;
	variable: string;
}

interface ProductCategory {
	id: number;
	name: string;
	slug: string;
}
interface ProductType {
	id: string;
	name: string;
}

interface Product {
	id: number;
	image_url: string;
	name: string;
	sku: string;
	regular_price: string;
	sale_price: string;
	stock_quantity: number | null;
	stock_status: string;
	product_type: string;
	backorders: string;
	wc_stock: boolean | string;
	category_name: string;
	variations: Product[];
}

interface ProductResponseType {
	products: Product[];
	total_products: number;
	max_num_pages: number;
}

type Query = {
	per_page: number;
	page: number;
};

const productsApi = apiSlice.injectEndpoints( {
	endpoints: ( builder ) => ( {
		getProducts: builder.query< ProductResponseType, Query >( {
			query: ( query ) => {
				const queryString = buildQueryString( query );
				return `product?${ queryString }`;
			},
			providesTags: [ 'Products' ],
		} ),

		enableStock: builder.mutation( {
			query: ( { id, body } ) => {
				return {
					method: 'PATCH',
					url: `/product/${ id }`,
					body,
				};
			},
			invalidatesTags: [ 'Products', 'ProductCount' ],
		} ),

		updateStockAction: builder.mutation( {
			query: ( { id, body } ) => {
				return {
					method: 'PATCH',
					url: `/product/${ id }`,
					body,
				};
			},
			invalidatesTags: [ 'Products', 'ProductCount' ],
		} ),

		getProductsCount: builder.query< ProductCount, void >( {
			query: () => 'product-count',
			providesTags: [ 'ProductCount' ],
		} ),

		getProductsCategories: builder.query< ProductCategory[], void >( {
			query: () => 'product-category',
			transformResponse: ( response: ProductCategory[] ) => {
				return [
					{ id: 0, name: 'All Category', slug: 'all' },
					...response,
				];
			},
		} ),

		getProductsType: builder.query< ProductType[], void >( {
			query: () => 'product-type',
			transformResponse: ( response: ProductTypeResponseType ) => {
				return [
					{ id: 'all', name: 'All Product' },
					...Object.entries( response ).map( ( [ key, value ] ) => ( {
						id: key,
						name: value,
					} ) ),
				];
			},
		} ),
	} ),
} );

export const {
	useGetProductsCountQuery,
	useGetProductsCategoriesQuery,
	useGetProductsTypeQuery,
	useGetProductsQuery,
	useEnableStockMutation,
	useUpdateStockActionMutation,
} = productsApi;
