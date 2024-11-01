// @ts-nocheck
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { useLocation, useNavigate, useSearchParams } from 'react-router-dom';
import { useGetBadgeQuery } from '../../../features/badges/badgesApi';
import { setCompleteBadgeState } from '../../../features/badges/badgesSlice';
import BadgeName from './components/Common/BadgeName';
import BadgeProductFiler from './components/Common/BadgeProductFiler';
import BadgeValidity from './components/Common/BadgeValidity';
import FooterActions from './components/Common/FooterActions';
import PreviewBadge from './components/Common/PreviewBadge';
import SelectBadgeType from './components/Common/SelectBadgeType';
import CustomSettings from './components/CustomSettings/CustomSettings';
import BadgePosition from './components/CustomSettings/components/BadgePosition';
import ImageSettings from './components/ImageSettings/ImageSettings';

const BadgesEditor = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const badgeSettings = useSelector((state) => state.badges);
  const [skip, setSkip] = useState(true);
  const { state } = useLocation();
  const [searchParams] = useSearchParams();
  const badgeId = searchParams.get('id');
  const [loading, setLoading] = useState(true);
  const { data, isLoading, isError } = useGetBadgeQuery(badgeId, { skip });

  useEffect(() => {
    if (state && badgeId) {
      dispatch(setCompleteBadgeState(state.badge));
      setLoading(false);
    } else {
      if (badgeId) {
        setSkip(false);
      } else {
        setLoading(false);
      }
    }
  }, [state, badgeId]);

  useEffect(() => {
    if (data) {
      dispatch(setCompleteBadgeState(data));
      setLoading(false);
      setSkip(true);
    }
  }, [data]);

  useEffect(() => {
    if (isError) {
      navigate('/badges');
    }
  }, [isError]);

  if (loading || isLoading) {
    return <div>Loading...</div>;
  }

  return (
    <div className='wmx-flex wmx-gap-4'>
      <div className='wmx-flex-grow'>
        <div className='wmx-py-2.5 wmx-shadow-md wmx-px-4 wmx-border-b wmx-rounded-lg wmx-sticky wmx-top-8 wmx-bg-[#E4EAF1] wmx-border-b-gray-100 wmx-mb-6'>
          <h3 className='wmx-text-2xl wmx-font-bold'>{badgeId ? __('Edit Badge') : __('Add New Badge')}</h3>
        </div>
        <BadgeName />
        <div>
          <BadgeProductFiler />
          <BadgeValidity />
          <SelectBadgeType />
          <BadgePosition />
          <div>{badgeSettings.badge_type === 'custom' ? <CustomSettings /> : <ImageSettings />}</div>
        </div>
      </div>

      <PreviewBadge />
      <FooterActions />
    </div>
  );
};
export default BadgesEditor;
