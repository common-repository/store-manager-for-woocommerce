import { apiSlice } from '../api/apiSlice';

const badgeApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    getBadges: builder.query({
      query: () => `badges`,
      providesTags: ['Badges'],
    }),

    getBadge: builder.query({
      query: (id) => `badges/${id}`,
      providesTags: ['Badges'],
    }),

    addBadge: builder.mutation({
      query: (body) => ({
        url: `badges`,
        method: 'POST',
        body,
      }),
      invalidatesTags: ['Badges'],
    }),

    updateBadge: builder.mutation({
      query: ({ id, body }) => ({
        url: `badges/${id}`,
        method: 'PUT',
        body,
      }),
      invalidatesTags: ['Badges'],
    }),

    deleteBadge: builder.mutation({
      query: (id) => ({
        url: `badges/${id}`,
        method: 'DELETE',
      }),
      invalidatesTags: ['Badges'],
    }),
  }),
});

export const {
  useGetBadgesQuery,
  useAddBadgeMutation,
  useDeleteBadgeMutation,
  useGetBadgeQuery,
  useUpdateBadgeMutation,
} = badgeApi;
