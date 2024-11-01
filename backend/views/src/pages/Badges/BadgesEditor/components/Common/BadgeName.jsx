// @ts-nocheck
import { useDispatch, useSelector } from 'react-redux';
import Input from '../../../../../components/Input';
import { changeBadgeBaseProperties } from '../../../../../features/badges/badgesSlice';
import SectionContainer from './SectionContainer';

const BadgeName = () => {
  const dispatch = useDispatch();
  const { badge_name } = useSelector((state) => state.badges);
  return (
    <SectionContainer>
      <div className='wmx-flex wmx-flex-col wmx-gap-2 wmx-items-center'>
        <div>
          <label className='wmx-block wmx-mb-1 wmx-text-lg wmx-font-medium' htmlFor='badge_name'>
            Badge Name<span className='wmx-text-red-500 wmx-text-xl'>*</span>
          </label>
          <Input
            id='badge_name'
            value={badge_name}
            className='wmx-w-96'
            onChange={(e) => {
              dispatch(changeBadgeBaseProperties({ name: 'badge_name', value: e.target.value }));
            }}
            placeholder='Your Badge Title'
          />
        </div>
      </div>
    </SectionContainer>
  );
};
export default BadgeName;
