import { useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { RootState } from '../../../app/store';
import Button from '../../../components/Button';
import Input from '../../../components/Input';
import SearchSelect from '../../../components/SearchSelect';
import { __ } from '@wordpress/i18n';

import {
	useGetProductsCategoriesQuery,
	useGetProductsTypeQuery,
} from '../../../features/products/productsApi';
import { changeQueryParams } from '../../../features/products/productsSlice';

const ProductFilters = () => {
	const { query } = useSelector( ( state: RootState ) => state.products );
	const dispatch = useDispatch();
	const { data: categories, isLoading } = useGetProductsCategoriesQuery();
	const { data: productType, isLoading: typeLoading } =
		useGetProductsTypeQuery();
	const [ searchValue, setSearchValue ] = useState( '' );

	const handleProductTypeChange = ( item: { id: string; name: string } ) => {
		if ( item.id === 'all' ) {
			dispatch( changeQueryParams( { ...query, type: '' } ) );
		} else {
			dispatch( changeQueryParams( { ...query, type: item.id } ) );
		}
	};

	const handleCategoryChange = ( item: {
		id: string;
		name: string;
		slug?: string;
	} ) => {
		if ( item.slug === 'all' ) {
			dispatch( changeQueryParams( { ...query, category: '' } ) );
		} else {
			dispatch( changeQueryParams( { ...query, category: item.slug } ) );
		}
	};

	const handleChange = ( e: React.ChangeEvent< HTMLInputElement > ) => {
		if ( e.target.value.trim() === '' ) {
			dispatch( changeQueryParams( { ...query, search: '' } ) );
		}
		setSearchValue( e.target.value.trim() );
	};

	const handleSearch = () => {
		dispatch(
			changeQueryParams( { ...query, search: searchValue, page: 1 } )
		);
	};

	return (
		<div className="wmx-mt-8 wmx-flex wmx-items-end wmx-justify-between">
			<div className="wmx-flex wmx-items-end wmx-gap-6">
				<div>
					<p className="wmx-text-[15px] wmx-font-medium wmx-text-gray-500 wmx-mb-1">
						{__('Select Category', 'store-manager-for-woocommerce')}
					</p>
					<SearchSelect
						items={ categories }
						selected={
							categories?.find(
								( type ) => type.slug === query.category
							) || {
								id: 'all',
								name: 'All Category',
								slug: 'all',
							}
						}
						onChange={ handleCategoryChange }
					/>
				</div>
				<div>
					<p className="wmx-text-[15px] wmx-font-medium wmx-text-gray-500 wmx-mb-1">
						{__('Select Product Type', 'store-manager-for-woocommerce')}
					</p>
					<SearchSelect
						selected={
							productType?.find(
								( type ) => type.id === query.type
							) || { id: 'all', name: 'All Product' }
						}
						onChange={ handleProductTypeChange }
						items={ productType }
					/>
				</div>
			</div>
			<div className="wmx-flex wmx-gap-4 wmx-items-center">
				<Input
					className="wmx-w-64"
					placeholder= { __('Search Product', 'store-manager-for-woocommerce' )}
					onChange={ handleChange }
					type="text"
				/>
				<Button onClick={ handleSearch }> { __('Search', 'store-manager-for-woocommerce' )}</Button>
			</div>
		</div>
	);
};
export default ProductFilters;
