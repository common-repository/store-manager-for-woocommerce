import { createApi, fetchBaseQuery } from '@reduxjs/toolkit/query/react';
declare const SMX: { rest_nonce: string; rest_url: string };

export const apiSlice = createApi({
  baseQuery: fetchBaseQuery({
    baseUrl: SMX.rest_url,
    prepareHeaders: (headers) => {
      return headers.set('X-WP-Nonce', SMX.rest_nonce);
    },
  }),
  tagTypes: ['Products', 'ProductCount', 'Badges'],
  endpoints: () => ({}),
});
