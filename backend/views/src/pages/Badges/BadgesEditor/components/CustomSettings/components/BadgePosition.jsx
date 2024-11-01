// @ts-nocheck
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import { changeBadgeSettingProperties } from '../../../../../../features/badges/badgesSlice';
import cn from '../../../../../../utils/cn';
import SectionContainer from '../../Common/SectionContainer';

const positionsButtons = [
  { name: 'Top Right', value: 'top-right' },
  { name: 'Top Left', value: 'top-left' },
  { name: 'Bottom Left', value: 'bottom-left' },
  { name: 'Bottom Right', value: 'bottom-right' },
  { name: 'Center', value: 'center' },
];

const BadgePosition = () => {
  const dispatch = useDispatch();
  const { badge_settings } = useSelector((state) => state.badges);

  const handleBadgePositionChange = (name, value) => {
    dispatch(changeBadgeSettingProperties({ name, value }));
  };

  return (
    <SectionContainer className='wmx-mt-6' title={__('Badge Position')}>
      <div className='wmx-flex wmx-gap-3 wmx-items-center '>
        {positionsButtons.map((button) => (
          <button
            key={button.value}
            onClick={() => handleBadgePositionChange('position', button.value)}
            className={cn('wmx-px-4 wmx-py-2.5 wmx-bg-white wmx-text-sm wmx-rounded-lg wmx-font-semibold', {
              'wmx-bg-primary wmx-text-white': badge_settings.position === button.value,
            })}
          >
            {button.name}
          </button>
        ))}
      </div>
    </SectionContainer>
  );
};
export default BadgePosition;
