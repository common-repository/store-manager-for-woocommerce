import { CheckIcon, NoSymbolIcon } from '@heroicons/react/24/outline';
import { useState } from 'react';
import { toast } from 'react-toastify';
import LoadingSpinner from '../../../../../components/LoadingSpinner';
import Table from '../../../../../components/Table';
import { useEnableStockMutation } from '../../../../../features/products/productsApi';
import cn from '../../../../../utils/cn';

type QuantityHoverType = {
	stockQuantity: number | null;
	id: number;
	className?: string;
};

const QuantityChange = ( {
	stockQuantity,
	id,
	className,
}: QuantityHoverType ) => {
	const [ quantity, setQuantity ] = useState( stockQuantity );
	const [ updateStock, { isLoading: updating } ] = useEnableStockMutation();
	const handleQuantityChange = (
		e: React.ChangeEvent< HTMLInputElement >
	) => {
		setQuantity( +e.target.value );
	};

	const handleUpdateQuantity = async () => {
		if ( stockQuantity === quantity || updating ) return;

		const result = await updateStock( {
			id,
			body: {
				stock_quantity: quantity,
				manage_stock: 'yes',
			},
		} ).unwrap();

		toast.success( 'Stock Quantity Updated' );
	};

	return (
		<Table.Data
			className={ cn(
				'wmx-group wmx-relative wmx-cursor-pointe',
				className
			) }
		>
			{ stockQuantity }
			<div className="wmx-text-white wmx-w-full wmx-hidden group-hover:wmx-flex wmx-absolute wmx-top-0 wmx-left-0 wmx-right-0 wmx-bottom-0">
				<input
					min={ 0 }
					onChange={ handleQuantityChange }
					value={ quantity || undefined }
					className="!wmx-shadow-none !wmx-w-full !wmx-rounded-none !wmx-px-0 !wmx-pl-1"
					type="number"
				/>
				{ updating ? (
					<div className="wmx-bg-primary wmx-px-1.5 wmx-w-11 wmx-flex wmx-justify-center wmx-items-center">
						<LoadingSpinner />
					</div>
				) : (
					<button
						disabled={ stockQuantity === quantity || updating }
						onClick={ handleUpdateQuantity }
						className="wmx-bg-primary wmx-px-1.5 wmx-w-8 wmx-flex wmx-justify-center wmx-items-center"
					>
						{ stockQuantity === quantity ? (
							<NoSymbolIcon className="wmx-h-5 wmx-w-5" />
						) : (
							<CheckIcon className="wmx-h-5 wmx-w-5" />
						) }
					</button>
				) }
			</div>
		</Table.Data>
	);
};

export default QuantityChange;
