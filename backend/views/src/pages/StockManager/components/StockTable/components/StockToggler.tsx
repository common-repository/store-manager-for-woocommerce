import { toast } from 'react-toastify';
import LoadingSpinner from '../../../../../components/LoadingSpinner';
import Toggler from '../../../../../components/Toggler';
import { useEnableStockMutation } from '../../../../../features/products/productsApi';

type ProductType = {
	product_type: string;
	id: number;
	stock_quantity: number | null;
};

type StockTogglerType = {
	checked: boolean;
	product: ProductType;
};

const StockToggler = ( { product, checked }: StockTogglerType ) => {
	const [ enableStock, { isLoading: togglerStatusLoading } ] =
		useEnableStockMutation();

	const handleWCStockToggle = async (
		id: number,
		status: boolean,
		quantity: number | null
	) => {
		const body = {
			manage_stock: status ? 'yes' : 'no',
			stock_quantity: quantity || 5,
		};

		const result = await enableStock( { id, body } ).unwrap();

		if ( result.wc_stock ) {
			toast.success( 'Stock Management Enabled' );
		} else {
			toast.warn( 'Stock Management Disabled' );
		}
	};

	return (
		<>
			{ ( product.product_type === 'simple' ||
				product.product_type === 'variable' ||
				product.product_type === 'variation' ) && (
				<div className="wmx-flex wmx-items-center wmx-gap-2">
					<Toggler
						onChange={ ( status ) =>
							handleWCStockToggle(
								product.id,
								status,
								product.stock_quantity
							)
						}
						checked={ checked }
					/>
					{ togglerStatusLoading ? (
						<LoadingSpinner />
					) : (
						<div className="wmx-h-4 wmx-w-4" />
					) }
				</div>
			) }
		</>
	);
};
export default StockToggler;
