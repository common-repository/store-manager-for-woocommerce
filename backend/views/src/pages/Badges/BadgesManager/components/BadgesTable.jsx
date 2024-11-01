// @ts-nocheck
import { Bars3Icon, PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline';
import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import DeleteModal from '../../../../components/DeleteModal';
import Table from '../../../../components/Table';
import TableLoading from '../../../../components/TableLoading';
import Toggler from '../../../../components/Toggler';
import {
  useDeleteBadgeMutation,
  useGetBadgesQuery,
  useUpdateBadgeMutation,
} from '../../../../features/badges/badgesApi';
import booleanConverter from '../../../../utils/booleanConverter';
import cn from '../../../../utils/cn';
import formatISODate from '../../../../utils/formatISODate';

const BadgesTable = ({ setSelectedBadge, selectedBadge }) => {
  const navigate = useNavigate();
  const { data, isLoading, isError } = useGetBadgesQuery('', {
    refetchOnMountOrArgChange: false,
  });
  const [updateBadge] = useUpdateBadgeMutation();
  const [deleteBadge, { isLoading: deleting }] = useDeleteBadgeMutation();
  const [sortedData, setSortedData] = useState([]);
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [deleteId, setDeleteId] = useState(null);

  const handleDelete = async () => {
    if (deleteId) {
      const result = await deleteBadge(deleteId).unwrap();
      if (result.id) {
        setShowDeleteModal(false);
        setDeleteId(null);
        toast.success('Badge Deleted Successfully');
      }
    }
  };

  const handleBadgeSelect = (badge) => {
    if (selectedBadge.id !== badge.id) {
      setSelectedBadge(badge);
    }
  };

  const handleBadgeStatus = (badge) => {
    if (badge.status === '0' || badge.status === '') {
      updateBadge({ id: badge.id, body: { status: '1' } });
    } else {
      updateBadge({ id: badge.id, body: { status: '0' } });
    }
  };

  useEffect(() => {
    if (isError) {
      setSortedData([]);
      setSelectedBadge(null);
      return;
    }

    if (data && data.length > 0) {
      const dataCopy = [...data];
      setSortedData(dataCopy.sort((a, b) => Number(b.priority) - Number(a.priority)));
      setSelectedBadge(dataCopy[0]);
    }
  }, [data, isError]);

  return (
    <div className='wmx-shadow wmx-rounded-lg wmx-overflow-hidden wmx-border wmx-border-gray-100'>
      <Table>
        <thead className='wmx-bg-white'>
          <tr>
            <Table.Heading className='wmx-max-w-8'>
              <span></span>
            </Table.Heading>
            <Table.Heading>Status</Table.Heading>
            <Table.Heading>Badge Name</Table.Heading>
            <Table.Heading>Valid From</Table.Heading>
            <Table.Heading>Valid To</Table.Heading>

            <Table.Heading className='wmx-w-20 wmx-text-center'>Actions</Table.Heading>
          </tr>
        </thead>
        {isLoading ? (
          <TableLoading type='badge' column={6} row={5} />
        ) : (
          <tbody className='wmx-divide-y wmx-divide-gray-200 wmx-bg-white'>
            {sortedData.map((badge) => (
              <tr
                className={cn({
                  'wmx-bg-primary/10': selectedBadge?.id === badge.id,
                })}
              >
                <Table.Data className='wmx-my-auto wmx-py-3 wmx-w-8'>
                  <Bars3Icon className='wmx-size-6' />
                </Table.Data>
                <Table.Data>
                  <div className='wmx-flex wmx-items-center'>
                    <Toggler onChange={() => handleBadgeStatus(badge)} checked={booleanConverter(badge.status)} />
                  </div>
                </Table.Data>
                <Table.Data onClick={() => handleBadgeSelect(badge)} className={cn('wmx-cursor-pointer')}>
                  {badge.badge_name}
                </Table.Data>
                <Table.Data>{formatISODate(badge.valid_from)}</Table.Data>
                <Table.Data>{formatISODate(badge.valid_to)}</Table.Data>
                <Table.Data>
                  <span className='wmx-flex wmx-items-center wmx-justify-center wmx-gap-2'>
                    <button
                      onClick={() => {
                        navigate(`editor?id=${badge.id}`, { state: { badge } });
                      }}
                    >
                      <PencilSquareIcon className='wmx-w-5 wmx-h-5' />
                    </button>
                    <button
                      onClick={() => {
                        setDeleteId(badge.id);
                        setShowDeleteModal(true);
                      }}
                    >
                      <TrashIcon className='wmx-w-5 wmx-h-5' />
                    </button>
                  </span>
                </Table.Data>
              </tr>
            ))}
          </tbody>
        )}
      </Table>
      <DeleteModal
        isOpen={showDeleteModal}
        setIsOpen={setShowDeleteModal}
        handleDelete={handleDelete}
        isLoading={deleting}
      />

      {isError && (
        <p className='wmx-p-6 wmx-flex wmx-items-center wmx-gap-2 wmx-border-t wmx-border-gray-200 wmx-text-lg wmx-font-medium wmx-bg-white'>
          No Badge Found,
          <button
            className='wmx-text-primary wmx-font-bold wmx-underline wmx-underline-offset-4'
            onClick={() => navigate('editor')}
          >
            Add New
          </button>
        </p>
      )}
    </div>
  );
};
export default BadgesTable;
