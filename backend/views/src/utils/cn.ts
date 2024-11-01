import { clsx } from 'clsx';
import { extendTailwindMerge } from 'tailwind-merge';

const prefixMerge = extendTailwindMerge( {
	prefix: 'wmx-',
} );

const cn = ( ...inputs: ( string | object | undefined )[] ): string => {
	return prefixMerge( clsx( ...inputs ) );
};

export default cn;
