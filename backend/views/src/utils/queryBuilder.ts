const buildQueryString = ( queryObject: {
	[ key: string ]: string | number | boolean;
} ): string => {
	const queryParams: string[] = [];

	for ( const key in queryObject ) {
		if ( queryObject.hasOwnProperty( key ) ) {
			const value = queryObject[ key ];
			if ( value !== '' ) {
				queryParams.push(
					`${ encodeURIComponent( key ) }=${ encodeURIComponent(
						value
					) }`
				);
			}
		}
	}

	return queryParams.join( '&' );
};

export default buildQueryString;
