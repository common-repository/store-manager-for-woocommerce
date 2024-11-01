// @ts-nocheck
import { useEffect } from 'react';
import { useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import Button from '../../../../../components/Button';
import LoadingSpinner from '../../../../../components/LoadingSpinner';
import { useAddBadgeMutation, useUpdateBadgeMutation } from '../../../../../features/badges/badgesApi';

const FooterActions = () => {
  const navigate = useNavigate();
  const badgeSettings = useSelector((state) => state.badges);
  const [adBadge, { data, isLoading, isSuccess }] = useAddBadgeMutation();
  const [updateBadge, { isLoading: isUpdating, isSuccess: isUpdated }] = useUpdateBadgeMutation();

  const handleSave = async () => {
    if (badgeSettings?.badge_name.trim() === '') {
      toast.error('Badge Name Is Required');
      return;
    }
    await adBadge(badgeSettings).unwrap();
  };

  const handleUpdate = async () => {
    await updateBadge({ id: badgeSettings.id, body: badgeSettings }).unwrap();
  };

  useEffect(() => {
    if (isSuccess) {
      toast.success('Badge Created Successfully');
      navigate(`?id=${data.id}`, { state: { badge: data } });
    }
  }, [isSuccess]);

  useEffect(() => {
    if (isUpdated) {
      toast.success('Badge Updated Successfully');
    }
  }, [isUpdated]);

  return (
    <div
      style={{ boxShadow: '0px -6px 10px 1px rgba(0,0,0,0.08)' }}
      className='wmx-fixed wmx-z-10 wmx-flex wmx-justify-end wmx-items-center wmx-gap-4 wmx-pe-10 wmx-bottom-0 wmx-left-[179px] wmx-right-0 wmx-border wmx-p-2.5 wmx-bg-white'
    >
      {badgeSettings?.id ? (
        <>
          <Button
            type='secondary'
            onClick={() => {
              navigate('/badges');
            }}
          >
            Cancel
          </Button>
          <Button disabled={isUpdating} onClick={handleUpdate}>
            {isUpdating ? (
              <span className='wmx-flex wmx-items-center wmx-gap-2'>
                Updating... <LoadingSpinner />
              </span>
            ) : (
              'Update Badge'
            )}
          </Button>
        </>
      ) : (
        <>
          <Button
            type='secondary'
            onClick={() => {
              navigate('/badges');
            }}
          >
            Cancel
          </Button>
          <Button disabled={isLoading} onClick={handleSave}>
            {isLoading ? (
              <span className='wmx-flex wmx-items-center wmx-gap-2'>
                Saving... <LoadingSpinner />
              </span>
            ) : (
              'Save Badge'
            )}
          </Button>
        </>
      )}
    </div>
  );
};
export default FooterActions;
