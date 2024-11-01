// @ts-nocheck
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import Input from '../../../../../../components/Input';
import Label from '../../../../../../components/Label';
import { changeBadgeSettingProperties } from '../../../../../../features/badges/badgesSlice';
import SectionContainer from '../../Common/SectionContainer';

const customDimensions = {
  height: 'Height',
  width: 'Width',
  borderWidth: 'Border Width',
};

const imageDimensions = {
  height: 'Height',
  width: 'Width',
  margin: 'Margin',
};

const BadgeDimension = () => {
  const dispatch = useDispatch();
  const { badge_settings, badge_type } = useSelector((state) => state.badges);

  const handleBadgeDimensionChange = (name, value) => {
    dispatch(changeBadgeSettingProperties({ name, value }));
  };

  return (
    <SectionContainer className='wmx-mt-6' title={__('Badge Dimensions')}>
      <div className='wmx-flex wmx-gap-2'>
        {Object.entries(badge_type === 'image' ? imageDimensions : customDimensions).map(([key, value]) => (
          <div className='wmx-flex wmx-flex-col wmx-gap-1'>
            <Label htmlFor={key}>{value}:</Label>
            <Input
              min={key !== 'margin' ? 0 : undefined}
              className='!wmx-outline-none wmx-w-36 !wmx-border-none focus:!wmx-shadow-none'
              id={key}
              type='number'
              value={badge_settings[key]}
              onChange={(e) => handleBadgeDimensionChange(key, e.target.value)}
            />
          </div>
        ))}
      </div>
    </SectionContainer>
  );
};
export default BadgeDimension;
