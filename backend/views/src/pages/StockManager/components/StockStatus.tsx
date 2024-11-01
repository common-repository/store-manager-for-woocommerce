import { CubeIcon, ExclamationTriangleIcon } from '@heroicons/react/24/outline';
import { ReactElement } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { RootState } from '../../../app/store';
import { useGetProductsCountQuery } from '../../../features/products/productsApi';
import { changeQueryParams } from '../../../features/products/productsSlice';
import cn from '../../../utils/cn';
import { __ } from '@wordpress/i18n';

type StatusCardPropsType = {
	title: string;
	count: number | string | undefined;
	className?: string;
	icon: ReactElement;
	active?: boolean;
	onClick: () => void;
	isLoading: boolean;
};

const StatusCard = ( {
	title,
	count,
	className,
	icon,
	active,
	onClick,
	isLoading,
}: StatusCardPropsType ) => {
	return (
		<button
			onClick={ onClick }
			className={ cn(
				'wmx-bg-white wmx-flex wmx-flex-col wmx-border-2 wmx-border-white wmx-py-4 wmx-px-6 wmx-w-48 wmx-rounded-lg ',
				className,
				{
					'wmx-border-primary': active,
				}
			) }
		>
			<span className="wmx-font-medium wmx-text-gray-600">{ title }</span>
			<span className="wmx-flex wmx-items-center wmx-gap-2 wmx-mt-0.5">
				{ icon }
				<span className="wmx-font-bold wmx-text-lg">
					{ isLoading ? '...' : count }
				</span>
			</span>
		</button>
	);
};

const StockStatus = () => {
	const { data, isLoading } = useGetProductsCountQuery();
	const dispatch = useDispatch();
	const { query } = useSelector( ( state: RootState ) => state.products );

	const handleCountFilterType = ( status: string ) => {
		dispatch( changeQueryParams( { ...query, status } ) );
	};

	return (
		<div className="wmx-flex wmx-gap-5">
			<StatusCard
				onClick={ () => handleCountFilterType( 'all' ) }
				active={ query.status === 'all' }
				title={ __('Total Products', 'store-manager-for-woocommerce')}
				isLoading={ isLoading }
				count={ data?.total_products }
				icon={ <CubeIcon className="wmx-w-6 wmx-h-6" /> }
			/>
			<StatusCard
				onClick={ () => handleCountFilterType( 'managed' ) }
				active={ query.status === 'managed' }
				title= { __('Managed By WC', 'store-manager-for-woocommerce')}
				isLoading={ isLoading }
				count={ data?.managed_products }
				icon={ <CubeIcon className="wmx-w-6 wmx-h-6" /> }
			/>
			<StatusCard
				onClick={ () => handleCountFilterType( 'low_stock' ) }
				active={ query.status === 'low_stock' }
				title={ __('Low In Stock', 'store-manager-for-woocommerce')}
				isLoading={ isLoading }
				count={ data?.low_stock_products }
				icon={ <ExclamationTriangleIcon className="wmx-w-6 wmx-h-6" /> }
			/>
			<StatusCard
				onClick={ () => handleCountFilterType( 'out_of_stock' ) }
				active={ query.status === 'out_of_stock' }
				title={ __('Out Of Stock', 'store-manager-for-woocommerce')}
				isLoading={ isLoading }
				count={ data?.out_of_stock_products }
				icon={ <ExclamationTriangleIcon className="wmx-w-6 wmx-h-6" /> }
			/>
		</div>
	);
};
export default StockStatus;
