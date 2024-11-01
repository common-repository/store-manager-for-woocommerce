import cn from '../utils/cn';

type InputType = {
  type?: 'text' | 'number';
  value?: string | number;
  className?: string | (() => string);
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  placeholder?: string;
  min?: number;
  id?: string;
  onFocus?: () => void;
};

const Input = ({
  type = 'text',
  value,
  className,
  onChange,
  placeholder = 'Placeholder',
  min,
  id,
  onFocus,
}: InputType) => {
  return (
    <input
      onFocus={onFocus}
      id={id}
      {...(type === 'number' && typeof min === 'number' && { min })}
      value={value}
      onChange={onChange}
      className={cn(
        '!wmx-rounded-md !wmx-border !wmx-border-gray-200 !wmx-bg-white !wmx-py-1 !wmx-shadow-none focus:!wmx-outline-none focus:!wmx-border-primary',
        className
      )}
      type={type}
      placeholder={placeholder}
    />
  );
};
export default Input;
