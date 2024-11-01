// @ts-nocheck
import { EyeDropperIcon } from '@heroicons/react/24/solid';
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import { changeBadgeSettingProperties } from '../../../../../../features/badges/badgesSlice';
import SectionContainer from '../../Common/SectionContainer';

const colors = {
  backgroundColor: 'Background Color',
  color: 'Text Color',
  borderColor: 'Border Color',
};

const BadgeColors = () => {
  const dispatch = useDispatch();
  const { badge_settings } = useSelector((state) => state.badges);

  const handleBadgeColorChange = (name, value) => {
    dispatch(changeBadgeSettingProperties({ name, value }));
  };

  return (
    <SectionContainer className='wmx-mt-6' title={__('Badge Colors')}>
      <div className='wmx-flex wmx-gap-6 wmx-items-center'>
        {Object.entries(colors).map(([key, value]) => (
          <div key={key} className='wmx-flex wmx-flex-col'>
            <label
              className='wmx-font-medium wmx-text-base wmx-flex wmx-items-center wmx-gap-2 wmx-tedxt'
              htmlFor={key}
            >
              {value}:
              <span
                style={{ borderColor: badge_settings[key] }}
                className='wmx-flex wmx-justify-center wmx-bg-white wmx-size-10 wmx-border-2 wmx-rounded-full wmx-items-center'
              >
                <EyeDropperIcon className='wmx-h-5 wmx-w-5' />
              </span>
            </label>
            <input
              className='!wmx-outline-none -wmx-mt-7 wmx-invisible !wmx-border-none focus:!wmx-shadow-none'
              id={key}
              type='color'
              value={badge_settings[key]}
              onChange={(e) => handleBadgeColorChange(key, e.target.value)}
            />
          </div>
        ))}
      </div>
    </SectionContainer>
  );
};
export default BadgeColors;
