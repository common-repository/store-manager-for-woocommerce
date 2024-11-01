// @ts-nocheck
import { Dialog, Transition } from '@headlessui/react';
import { Fragment } from 'react';
import Button from './Button';
import LoadingSpinner from './LoadingSpinner';

const DeleteModal = ({ isOpen, setIsOpen, handleDelete, isLoading }) => {
  return (
    <Transition appear show={isOpen} as={Fragment}>
      <Dialog as='div' className='wmx-relative wmx-z-10' onClose={() => setIsOpen(false)}>
        <div className='wmx-fixed wmx-inset-0 wmx-bg-black/25' />

        <div className='wmx-fixed wmx-inset-0 wmx-overflow-y-auto'>
          <div className='wmx-flex wmx-min-h-full wmx-items-center wmx-justify-center wmx-p-4 wmx-text-center'>
            <Dialog.Panel className='wmx-w-full wmx-max-w-md wmx-transform wmx-overflow-hidden wmx-rounded-xl wmx-bg-white wmx-p-6 wmx-text-left wmx-align-middle wmx-shadow-xl wmx-transition-all'>
              <Dialog.Title
                as='h3'
                className='wmx-text-lg wmx-font-medium wmx-leading-6 wmx-capitalize wmx-text-gray-900'
              >
                Are you sure you want to delete this item?
              </Dialog.Title>

              <div className='wmx-mt-1'>
                <p className='wmx-text-sm wmx-text-gray-600 wmx-capitalize'>This action cannot be undone.</p>

                <div className='wmx-mt-6 wmx-flex wmx-justify-end wmx-items-center wmx-gap-3'>
                  <Button type='secondary' onClick={() => setIsOpen(false)}>
                    Cancel
                  </Button>

                  <Button type='danger' onClick={handleDelete}>
                    {isLoading ? (
                      <span className='wmx-flex wmx-items-center wmx-gap-2'>
                        Deleting <LoadingSpinner />
                      </span>
                    ) : (
                      'Delete'
                    )}
                  </Button>
                </div>
              </div>
            </Dialog.Panel>
          </div>
        </div>
      </Dialog>
    </Transition>
  );
};

export default DeleteModal;
