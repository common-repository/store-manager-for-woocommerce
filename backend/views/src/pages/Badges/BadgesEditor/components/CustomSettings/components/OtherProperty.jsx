// @ts-nocheck
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import Input from '../../../../../../components/Input';
import Label from '../../../../../../components/Label';
import { changeBadgeSettingProperties } from '../../../../../../features/badges/badgesSlice';
import SectionContainer from '../../Common/SectionContainer';

const borderRadiusInputs = [
  { name: 'Top Left', value: 'borderTopLeftRadius' },
  { name: 'Top Right', value: 'borderTopRightRadius' },
  { name: 'Bottom Left', value: 'borderBottomLeftRadius' },
  { name: 'Bottom Right', value: 'borderBottomRightRadius' },
];
const OtherProperty = () => {
  const dispatch = useDispatch();
  const { badge_settings } = useSelector((state) => state.badges);

  const handleStyleChange = (name, value) => {
    dispatch(changeBadgeSettingProperties({ name, value }));
  };

  return (
    <SectionContainer className='wmx-mt-6 wmx-mb-4' title={__('Other Properties')}>
      <div className='wmx-flex wmx-items-center wmx-gap-4'>
        <div className='wmx-flex wmx-flex-col wmx-gap-0.5'>
          <Label htmlFor='margin'>Margin:</Label>
          <Input
            id='margin'
            type='number'
            value={badge_settings.margin}
            onChange={(e) => handleStyleChange('margin', e.target.value)}
          />
        </div>

        <div className='wmx-flex wmx-flex-col wmx-gap-0.5'>
          <Label>Border Radius:</Label>
          <div className='wmx-flex wmx-gap-2'>
            {borderRadiusInputs.map((input) => (
              <Input
                className='wmx-w-16'
                key={input.value}
                id={input.value}
                type='number'
                value={badge_settings[input.value]}
                placeholder={input.name}
                onChange={(e) => handleStyleChange(input.value, e.target.value)}
              />
            ))}
          </div>
        </div>
      </div>
    </SectionContainer>
  );
};
export default OtherProperty;
