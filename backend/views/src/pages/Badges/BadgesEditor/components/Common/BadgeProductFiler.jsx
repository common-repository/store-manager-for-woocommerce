// @ts-nocheck
import { XMarkIcon } from '@heroicons/react/24/solid';
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import AsyncSearchSelect from '../../../../../components/AsyncSearchSelect';
import { changeBadgeBaseProperties, changeBadgeSettingProperties } from '../../../../../features/badges/badgesSlice';
import cn from '../../../../../utils/cn';
import SectionContainer from './SectionContainer';

const filters = {
  all: 'All Products',
  few: 'Selected Products',
};

const BadgeProductFiler = () => {
  const dispatch = useDispatch();
  const { badge_settings, filter } = useSelector((state) => state.badges);

  const handleFilterTypeChange = (filter_type) => {
    if (filter_type === badge_settings.filterType) return;
    dispatch(changeBadgeSettingProperties({ name: 'filterType', value: filter_type }));
    if (filter_type === 'few') {
      dispatch(changeBadgeBaseProperties({ name: 'filter', value: [] }));
    } else {
      dispatch(changeBadgeBaseProperties({ name: 'filter', value: 'all' }));
    }
  };

  return (
    <SectionContainer className='wmx-mt-6' title={__('Select Products')}>
      <div className='wmx-flex wmx-justify-center wmx-items-center wmx-gap-4'>
        {Object.entries(filters).map(([key, value]) => (
          <button
            onClick={() => handleFilterTypeChange(key)}
            key={key}
            className={cn('wmx-bg-white wmx-font-semibold wmx-w-44 wmx-h-10 wmx-rounded-lg', {
              'wmx-bg-primary wmx-text-white': key === badge_settings.filterType,
            })}
          >
            {value}
          </button>
        ))}
      </div>

      {badge_settings.filterType === 'few' && (
        <div className='wmx-flex wmx-mt-4 wmx-gap-4'>
          <AsyncSearchSelect />
          <div className='wmx-flex wmx-gap-2 wmx-items-start wmx-flex-wrap wmx-bg-white wmx-flex-grow wmx-p-2 wmx-min-h-20 wmx-rounded-lg wmx-border wmx-border-gray-200'>
            {Array.isArray(filter) && filter.length > 0 ? (
              filter.map((product) => (
                <div
                  className='wmx-flex wmx-gap-0.5 wmx-items-center wmx-bg-gray-100 wmx-p-1 wmx-rounded-md'
                  key={product.id}
                >
                  <span className='wmx-text-sm'>{product.name}</span>
                  <button
                    onClick={() =>
                      dispatch(
                        changeBadgeBaseProperties({ name: 'filter', value: filter.filter((p) => p.id !== product.id) })
                      )
                    }
                    className='wmx-p-1'
                  >
                    <XMarkIcon className='wmx-size-3.5 wmx-text-red-600' />
                  </button>
                </div>
              ))
            ) : (
              <span className='wmx-text-gray-500 wmx-text-sm'>Please Select Some Products</span>
            )}
          </div>
        </div>
      )}
    </SectionContainer>
  );
};
export default BadgeProductFiler;
