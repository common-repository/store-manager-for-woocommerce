import { PencilSquareIcon, TrashIcon } from '@heroicons/react/24/outline';
import Table from '../../../components/Table';

const FiltersTable = () => {
  return (
    <Table>
      <thead className='wmx-bg-white'>
        <tr>
          <Table.Heading className='wmx-pl-4 '>Filter Name</Table.Heading>
          <Table.Heading className='wmx-w-32 wmx-text-center'>Conditions</Table.Heading>
          <Table.Heading className='wmx-w-20 wmx-text-center'>Actions</Table.Heading>
        </tr>
      </thead>
      <tbody className='wmx-divide-y wmx-divide-gray-200 wmx-bg-white'>
        <tr>
          <Table.Data className='wmx-pl-4 wmx-my-auto wmx-py-3'>All Product Except New Arrival</Table.Data>
          <Table.Data className='wmx-text-center'>3</Table.Data>
          <Table.Data>
            <span className='wmx-flex wmx-items-center wmx-justify-center wmx-gap-2'>
              <button>
                <TrashIcon className='wmx-w-5 wmx-h-5' />
              </button>
              <button>
                <PencilSquareIcon className='wmx-w-5 wmx-h-5' />
              </button>
            </span>
          </Table.Data>
        </tr>
        <tr>
          <Table.Data className='wmx-pl-4 wmx-my-auto wmx-py-3'>Only Winter Category Product Under 5$</Table.Data>
          <Table.Data className='wmx-text-center'>5</Table.Data>
          <Table.Data>
            <span className='wmx-flex wmx-items-center wmx-justify-center wmx-gap-2'>
              <button>
                <TrashIcon className='wmx-w-5 wmx-h-5' />
              </button>
              <button>
                <PencilSquareIcon className='wmx-w-5 wmx-h-5' />
              </button>
            </span>
          </Table.Data>
        </tr>
      </tbody>
    </Table>
  );
};
export default FiltersTable;
