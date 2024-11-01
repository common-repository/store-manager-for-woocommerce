// @ts-nocheck
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import Input from '../../../../../components/Input';
import Label from '../../../../../components/Label';
import { changeBadgeBaseProperties } from '../../../../../features/badges/badgesSlice';
import formatToLocalDateTime from '../../../../../utils/formatToLocalDateTime';
import SectionContainer from './SectionContainer';

const BadgeValidity = () => {
  const dispatch = useDispatch();
  const { valid_from, valid_to } = useSelector((state) => state.badges);

  const handleBadgeSettingPropertiesChange = (name, value) => {
    dispatch(changeBadgeBaseProperties({ name, value }));
  };

  return (
    <SectionContainer className='wmx-mt-6' title={__('Badge Validity')}>
      <div className='wmx-flex wmx-items-center wmx-gap-4'>
        <div className='wmx-flex wmx-flex-col wmx-gap-1'>
          <Label htmlFor='validFrom'>Start Date:</Label>
          <Input
            id='validFrom'
            className='wmx-w-full'
            type='datetime-local'
            value={formatToLocalDateTime(valid_from)}
            onChange={(e) => handleBadgeSettingPropertiesChange('valid_from', e.target.value)}
          />
        </div>

        <div className='wmx-flex wmx-flex-col wmx-gap-1'>
          <Label htmlFor='validTo'>End Date:</Label>
          <Input
            id='validTo'
            type='datetime-local'
            min={0}
            value={formatToLocalDateTime(valid_to)}
            className='wmx-w-full'
            onChange={(e) => handleBadgeSettingPropertiesChange('valid_to', e.target.value)}
          />
        </div>
      </div>
    </SectionContainer>
  );
};
export default BadgeValidity;
