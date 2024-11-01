import { Combobox, Transition } from '@headlessui/react';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/react/20/solid';
import { Fragment, useState } from 'react';

export type ItemType = {
  id: number | string;
  name: string;
  slug?: string;
};

type SearchSelectPropType = {
  items: ItemType[] | undefined;
  selected: ItemType;
  onChange: (item: { id: string; name: string; slug?: string }) => void;
};

const SearchSelect = ({ items, onChange, selected }: SearchSelectPropType) => {
  const [query, setQuery] = useState('');

  const filteredItems =
    query === ''
      ? items || []
      : (items || []).filter((item) =>
          item.name.toLowerCase().replace(/\s+/g, '').includes(query.toLowerCase().replace(/\s+/g, ''))
        );

  return (
    <Combobox value={selected} onChange={onChange}>
      <div className='wmx-relative'>
        <div className='wmx-relative wmx-w-60 wmx-cursor-default !wmx-border !wmx-border-gray-200 wmx-overflow-hidden wmx-rounded-lg wmx-bg-white wmx-text-left focus:wmx-outline-none focus-visible:wmx-ring-2 focus-visible:wmx-ring-white/75 focus-visible:wmx-ring-offset-2 focus-visible:wmx-ring-offset-teal-300'>
          <Combobox.Input
            className='wmx-text-base wmx-border-none !wmx-border-0 wmx-w-full  !wmx-py-1 !wmx-pl-3 !wmx-pr-10 wmx-leading-5 wmx-text-gray-900 focus:wmx-ring-0 focus:!wmx-shadow-none'
            displayValue={(item: ItemType) => item.name}
            onChange={(event) => setQuery(event.target.value)}
          />
          <Combobox.Button className='wmx-absolute wmx-inset-y-0 wmx-right-0 wmx-flex wmx-items-center wmx-pr-2'>
            <ChevronUpDownIcon className='wmx-h-5 wmx-w-5 wmx-text-gray-400' aria-hidden='true' />
          </Combobox.Button>
        </div>
        <Transition
          as={Fragment}
          leave='wmx-transition wmx-ease-in wmx-duration-100'
          leaveFrom='wmx-opacity-100'
          leaveTo='wmx-opacity-0'
          afterLeave={() => setQuery('')}
        >
          <Combobox.Options className='custom_scrollbar wmx-shadow-lg wmx-z-50 wmx-absolute wmx-rounded wmx-mt-1.5 wmx-max-h-60 wmx-w-full wmx-overflow-auto wmx-bg-white wmx-text-base focus:wmx-outline-none'>
            {filteredItems.length === 0 && query !== '' ? (
              <div className='wmx-relative wmx-cursor-default wmx-select-none wmx-px-4 wmx-py-2 wmx-text-gray-700'>
                Nothing found.
              </div>
            ) : (
              filteredItems.map((item) => (
                <Combobox.Option
                  key={item.id}
                  className={({ active }) =>
                    `wmx-relative wmx-mb-0 wmx-cursor-default wmx-capitalize wmx-select-none wmx-py-1 wmx-pl-10 wmx-pr-4 ${
                      active ? 'wmx-bg-primary wmx-text-white' : 'wmx-text-gray-900'
                    }`
                  }
                  value={item}
                >
                  {({ selected: s, active }) => (
                    <>
                      <span className={`wmx-block wmx-truncate ${s ? 'wmx-font-medium' : 'wmx-font-normal'}`}>
                        {item.name}
                      </span>
                      {s ? (
                        <span
                          className={`wmx-absolute wmx-inset-y-0 wmx-left-0 wmx-flex wmx-items-center wmx-pl-3 ${
                            active ? 'wmx-text-white' : 'wmx-text-primary'
                          }`}
                        >
                          <CheckIcon className='wmx-h-5 wmx-w-5' aria-hidden='true' />
                        </span>
                      ) : null}
                    </>
                  )}
                </Combobox.Option>
              ))
            )}
          </Combobox.Options>
        </Transition>
      </div>
    </Combobox>
  );
};

export default SearchSelect;
