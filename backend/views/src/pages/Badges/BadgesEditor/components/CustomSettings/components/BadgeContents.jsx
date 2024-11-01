// @ts-nocheck
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import Input from '../../../../../../components/Input';
import Label from '../../../../../../components/Label';
import { changeBadgeSettingProperties } from '../../../../../../features/badges/badgesSlice';
import SectionContainer from '../../Common/SectionContainer';

const BadgeContents = () => {
  const dispatch = useDispatch();
  const { badge_settings } = useSelector((state) => state.badges);

  const handleBadgeContentChange = (name, value) => {
    dispatch(changeBadgeSettingProperties({ name, value }));
  };

  const handleCopy = (value) => {
    window.navigator.clipboard.writeText(value);
  };

  return (
    <SectionContainer className='wmx-mt-6' title={__('Badge Contents')}>
      <div className='wmx-flex wmx-items-center wmx-gap-4'>
        <div className='wmx-flex wmx-flex-grow wmx-max-w-80c wmx-flex-col wmx-gap-1'>
          <Label htmlFor='badge-text'>Badge Text:</Label>
          <Input
            id='badge-text'
            placeholder='Summer Sell'
            className='wmx-w-full'
            type='text'
            value={badge_settings.text}
            onChange={(e) => handleBadgeContentChange('text', e.target.value)}
          />
        </div>

        <div className='wmx-flex wmx-flex-col wmx-w-32 wmx-gap-1'>
          <Label htmlFor='fontSize'>Font Size:</Label>
          <Input
            id='fontSize'
            type='number'
            min={0}
            value={badge_settings.fontSize}
            className='wmx-w-full'
            onChange={(e) => handleBadgeContentChange('fontSize', e.target.value)}
          />
        </div>

        <div className='wmx-flex wmx-flex-col wmx-gap-1'>
          <Label htmlFor='fontWeight'>Font Weight:</Label>
          <select
            className='!wmx-border !wmx-rounded-md !wmx-w-32 !wmx-shadow-none !wmx-border-gray-200 focus:!wmx-border-primary !wmx-bg-white !wmx-py-1.5'
            id='fontWeight'
            value={badge_settings.fontWeight}
            onChange={(e) => handleBadgeContentChange('fontWeight', e.target.value)}
          >
            <option value='400'>Normal</option>
            <option value='500'>Medium</option>
            <option value='600'>Semi Bold</option>
            <option value='700'>Bold</option>
          </select>
        </div>
      </div>

      {/* Badge Placeholder Tips */}

      {/* <p className='wmx-text-sm wmx-mt-4 wmx-max-w-4xl wmx-leading-6'>
        <span className='wmx-font-semibold'>Tip: </span>Use placeholders like{' '}
        <code
          title='Click To Copy!'
          className='wmx-cursor-pointer'
          onClick={() => handleCopy('{{discount_percentage}}')}
        >{`{{discount_percentage}}`}</code>
        ,{' '}
        <code
          title='Click To Copy!'
          className='wmx-cursor-pointer'
          onClick={() => handleCopy('{{discount_value}}')}
        >{`{{discount_value}}`}</code>
        ,{' '}
        <code
          title='Click To Copy!'
          className='wmx-cursor-pointer'
          onClick={() => handleCopy('{{regular_price}}')}
        >{`{{regular_price}}`}</code>
        , and{' '}
        <code
          title='Click To Copy!'
          className='wmx-cursor-pointer'
          onClick={() => handleCopy('{{sale_price}}')}
        >{`{{sale_price}}`}</code>{' '}
        to show discount and price details.
      </p> */}
    </SectionContainer>
  );
};
export default BadgeContents;
