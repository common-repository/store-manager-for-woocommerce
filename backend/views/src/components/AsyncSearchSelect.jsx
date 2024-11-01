// @ts-nocheck
import { useEffect, useRef, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { changeBadgeBaseProperties } from '../features/badges/badgesSlice';
import { useGetProductsQuery } from '../features/products/productsApi';
import cn from '../utils/cn';
import Input from './Input';
import LoadingSpinner from './LoadingSpinner';

const AsyncSearchSelect = () => {
  const [query, setQuery] = useState({ per_page: 10, status: 'all', search: '' });
  const [searchTerm, setSearchTerm] = useState('');
  const [isDropdownVisible, setDropdownVisible] = useState(false);
  const { data, isLoading, isFetching } = useGetProductsQuery(query, { skip: !query.search });
  const { filter } = useSelector((state) => state.badges);
  const dispatch = useDispatch();
  const dropdownRef = useRef(null);

  // Update query when search term changes
  useEffect(() => {
    const handler = setTimeout(() => {
      setQuery((prevState) => ({ ...prevState, search: searchTerm.trim() }));
    }, 500);

    return () => clearTimeout(handler);
  }, [searchTerm]);

  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setDropdownVisible(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, [dropdownRef]);

  const handleQueryChange = (e) => {
    setSearchTerm(e.target.value);
    setDropdownVisible(true);
  };

  const handleAddToFilter = (product) => {
    if (filter.some((item) => item.id === product.id)) {
      dispatch(changeBadgeBaseProperties({ name: 'filter', value: filter.filter((item) => item.id !== product.id) }));
    } else {
      dispatch(
        changeBadgeBaseProperties({ name: 'filter', value: [...filter, { id: product.id, name: product.name }] })
      );
    }
  };

  return (
    <div className='wmx-relative wmx-w-72 wmx-flex-shrink-0'>
      <div className='wmx-relative'>
        <Input
          onFocus={() => setDropdownVisible(true)}
          value={searchTerm}
          type='text'
          onChange={handleQueryChange}
          placeholder='Search Products'
          className='wmx-w-full !wmx-pr-8'
        />
        <div className='wmx-absolute  wmx-p-2 wmx-pr-3 wmx-flex wmx-justify-center wmx-items-center wmx-right-0 wmx-top-0 wmx-bottom-0'>
          {isLoading || isFetching ? <LoadingSpinner /> : <div className='wmx-size-4' />}
        </div>
      </div>
      {isDropdownVisible && searchTerm && data && (
        <div
          ref={dropdownRef}
          className='wmx-absolute wmx-w-full wmx-mt-1 custom_scrollbar wmx-bg-white wmx-border wmx-border-gray-300 wmx-rounded-lg wmx-shadow-lg wmx-max-h-60 wmx-overflow-y-auto wmx-z-10'
        >
          {data?.products?.length > 0 ? (
            <ul className='wmx-divide-y wmx-divide-gray-200'>
              {data.products.map((product) => (
                <li
                  onClick={() => handleAddToFilter(product)}
                  key={product.id}
                  className={cn(
                    'wmx-p-1.5 wmx-flex wmx-gap-1 wmx-items-center wmx-text-sm wmx-m-0 hover:wmx-bg-primary/5 wmx-cursor-pointer',
                    filter.some((item) => item.id === product.id) && 'wmx-bg-primary/5'
                  )}
                >
                  <img className='wmx-w-7 wmx-h-7 wmx-rounded' src={product.image_url} alt={product.name} />
                  <span className='wmx-truncate'>{product.name}</span>
                </li>
              ))}
            </ul>
          ) : (
            <div className='wmx-p-2 wmx-text-sm wmx-m-0 wmx-text-gray-600 wmx-text-center'>No Products Found</div>
          )}
        </div>
      )}
    </div>
  );
};

export default AsyncSearchSelect;
