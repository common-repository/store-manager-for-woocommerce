type LoaderType = {
	row?: number;
	column?: number;
};

const TableLoading = ( { column = 10, row = 10 }: LoaderType ) => {
	return (
		<tbody className="wmx-divide-y wmx-divide-gray-200 wmx-bg-white">
			{ Array( row )
				.fill( 'item' )
				.map( ( _, i ) => (
					<tr key={ i }>
						{ Array( column )
							.fill( 'item' )
							.map( ( item, index ) => (
								<td key={ index } className="wmx-py-4 wmx-px-2">
									<div className="wmx-h-5 wmx-w-full wmx-bg-gray-300 wmx-animate-pulse wmx-rounded-full"></div>
								</td>
							) ) }
					</tr>
				) ) }
		</tbody>
	);
};
export default TableLoading;
