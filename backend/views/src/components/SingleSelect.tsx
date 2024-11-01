import { Listbox, Transition } from '@headlessui/react';
import { ChevronUpDownIcon } from '@heroicons/react/20/solid';
import { Fragment } from 'react';

type SingleSelectType = {
  items: { [key: string]: string };
  selected: string;
  onChange?: (changedStatus: string) => void;
};

const SingleSelect = ({ items, selected, onChange }: SingleSelectType) => {
  return (
    <Listbox onChange={onChange} value={selected}>
      <div className='wmx-relative'>
        <Listbox.Button className='wmx-relative wmx-w-44 wmx-text-sm wmx-border wmx-border-gray-200 wmx-cursor-default wmx-rounded-lg wmx-bg-white wmx-py-1 wmx-pl-2 wmx-pr-10 wmx-text-left focus:wmx-outline-none'>
          <span className='wmx-block wmx-truncate'>{items[selected]}</span>
          <span className='wmx-pointer-events-none wmx-absolute wmx-inset-y-0 wmx-right-0 wmx-flex wmx-items-center pr-2'>
            <ChevronUpDownIcon className='wmx-h-5 wmx-w-5 wmx-text-gray-400' aria-hidden='true' />
          </span>
        </Listbox.Button>
        <Transition
          as={Fragment}
          leave='wmx-transition wmx-ease-in wmx-duration-100'
          leaveFrom='wmx-opacity-100'
          leaveTo='wmx-opacity-0'
        >
          <Listbox.Options className='wmx-absolute wmx-border wmx-z-50 wmx-mt-1 wmx-w-full wmx-rounded wmx-bg-white wmx-shadow-lg'>
            {Object.keys(items).map((action, index) => (
              <Listbox.Option
                key={index}
                className={({ active }) =>
                  `wmx-text-gray-900 wmx-m-0 wmx-cursor-default wmx-outline-none wmx-select-none wmx-text-sm wmx-p-1 wmx-px-2 ${
                    active ? 'wmx-bg-gray-200' : ''
                  }`
                }
                value={action}
              >
                {({ active }) => (
                  <span className={`wmx-block wmx-truncate ${active ? 'wmx-font-bold' : 'wmx-font-normal'}`}>
                    {items[action]}
                  </span>
                )}
              </Listbox.Option>
            ))}
          </Listbox.Options>
        </Transition>
      </div>
    </Listbox>
  );
};

export default SingleSelect;
