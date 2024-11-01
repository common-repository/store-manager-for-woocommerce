import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/react/24/outline';
import { useDispatch, useSelector } from 'react-redux';
import { RootState } from '../../../../app/store';
import { useGetProductsQuery } from '../../../../features/products/productsApi';
import { changeQueryParams } from '../../../../features/products/productsSlice';

const Pagination = () => {
  const { query } = useSelector((state: RootState) => state.products);

  const { data, isLoading } = useGetProductsQuery(query, {
    refetchOnMountOrArgChange: true,
  });

  const dispatch = useDispatch();
  const handleNext = () => {
    if (data?.max_num_pages === 0 || data?.max_num_pages === query.page) return;
    dispatch(changeQueryParams({ ...query, page: query.page + 1 }));
  };
  const handlePrev = () => {
    if (query.page === 1) return;
    dispatch(changeQueryParams({ ...query, page: query.page - 1 }));
  };

  if (isLoading) return; // TODO: Add Loading State
  return (
    <div className='wmx-bg-white wmx-py-2 wmx-px-4 wmx-border-t wmx-flex wmx-justify-between wmx-items-center'>
      {data && data?.total_products > 0 ? (
        <p>
          Showing {query.page * query.per_page - query.per_page + 1} -{' '}
          {query.page * query.per_page > data?.total_products ? data?.total_products : query.page * query.per_page} of{' '}
          {data?.total_products} Products
        </p>
      ) : (
        <p>No Product Found.</p>
      )}
      <div className=' wmx-flex wmx-items-center'>
        <button
          disabled={query.page === 1}
          onClick={handlePrev}
          className='wmx-border wmx-border-r-0 wmx-pl-2 wmx-pr-2.5 wmx-py-1.5 wmx-rounded-l wmx-bg-white  wmx-text-gray-800 hover:wmx-bg-primary hover:wmx-text-white disabled:wmx-cursor-not-allowed disabled:wmx-bg-white disabled:wmx-text-gray-400'
        >
          <ChevronLeftIcon className='wmx-h-5 wmx-w-5 ' />
        </button>
        <button
          disabled={data?.max_num_pages === 0 || data?.max_num_pages === query.page}
          onClick={handleNext}
          className='wmx-border wmx-pr-2 wmx-pl-2.5 wmx-py-1.5 wmx-rounded-r wmx-bg-white  wmx-text-gray-800 hover:wmx-bg-primary hover:wmx-text-white disabled:wmx-cursor-not-allowed disabled:wmx-bg-white disabled:wmx-text-gray-400'
        >
          <ChevronRightIcon className='wmx-h-5 wmx-w-5' />
        </button>
      </div>
    </div>
  );
};
export default Pagination;
