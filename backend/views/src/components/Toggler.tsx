import { Switch } from '@headlessui/react';

type TogglerType = {
  onChange: (status: boolean) => void;
  checked: boolean;
};

const Toggler = ({ onChange, checked }: TogglerType) => {
  return (
    <Switch
      checked={checked}
      onChange={onChange}
      className={`${checked ? 'wmx-bg-primary' : 'wmx-bg-gray-200'}
          wmx-relative wmx-inline-flex wmx-h-[18] wmx-w-[30px] wmx-shrink-0 wmx-cursor-pointer wmx-rounded-full wmx-border-2 wmx-border-transparent wmx-transition-colors wmx-duration-200 wmx-ease-in-out focus:wmx-outline-none focus-visible:wmx-ring-2  focus-visible:wmx-ring-white/75`}
    >
      <span
        aria-hidden='true'
        className={`${checked ? 'wmx-translate-x-[13px]' : 'wmx-translate-x-0'}
            wmx-pointer-events-none wmx-inline-block wmx-h-[13px] wmx-w-[13px] wmx-transform wmx-rounded-full wmx-bg-white wmx-ring-0 wmx-transition wmx-duration-200 wmx-ease-in-out`}
      />
    </Switch>
  );
};

export default Toggler;
