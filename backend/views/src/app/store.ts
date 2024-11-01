import { configureStore } from '@reduxjs/toolkit';
import badgesReducer from '../features/badges/badgesSlice';
import productsReducer from '../features/products/productsSlice';
import { apiSlice } from './../features/api/apiSlice';

const store = configureStore({
  reducer: {
    [apiSlice.reducerPath]: apiSlice.reducer,
    products: productsReducer,
    badges: badgesReducer,
  },
  middleware: (getDefaultMiddleware) => {
    return getDefaultMiddleware().concat(apiSlice.middleware);
  },
});

export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;

export default store;
