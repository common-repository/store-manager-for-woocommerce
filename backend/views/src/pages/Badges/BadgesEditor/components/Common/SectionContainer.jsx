// @ts-nocheck
import cn from '../../../../../utils/cn';

const SectionContainer = ({ title, children, className }) => {
  return (
    <div className={cn('wmx-bg-primary/5 wmx-rounded-lg wmx-border wmx-border-gray-100 wmx-shadow-sm', className)}>
      {title && (
        <h2 className='wmx-text-lg wmx-border-b wmx-border-b-gray-100 wmx-font-semibold wmx-px-4 wmx-py-2.5 wmx-bg-primary/5 wmx-rounded-t-lg'>
          {title}
        </h2>
      )}

      <div className='wmx-p-6'>{children}</div>
    </div>
  );
};
export default SectionContainer;
