import { createSlice } from '@reduxjs/toolkit';

type QueryType = {
	per_page: number;
	page: number;
	type: string;
	category: string;
	search: string;
	status: string;
};

interface ProductsState {
	query: QueryType;
}

const initialState: ProductsState = {
	query: {
		per_page: 10,
		page: 1,
		type: '',
		category: '',
		search: '',
		status: 'all',
	},
};

const productsSlice = createSlice( {
	name: 'products',
	initialState,
	reducers: {
		changeQueryParams: ( state, action ) => {
			state.query = action.payload;
		},
	},
} );

export const { changeQueryParams } = productsSlice.actions;
export default productsSlice.reducer;
