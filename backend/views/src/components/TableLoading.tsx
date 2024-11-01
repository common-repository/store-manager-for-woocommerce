import cn from '../utils/cn';

type LoaderType = {
  row?: number;
  column?: number;
  type?: string;
};

const TableLoading = ({ column = 10, row = 10, type = '' }: LoaderType) => {
  return (
    <tbody className='wmx-divide-y wmx-divide-gray-200 wmx-bg-white'>
      {Array(row)
        .fill('item')
        .map((_, i) => (
          <tr key={i}>
            {Array(column)
              .fill('item')
              .map((item, index) => (
                <td key={index} className={cn('wmx-py-3.5 wmx-px-2', { 'wmx-w-10': index === 0 && type === 'badge' })}>
                  <div
                    className={cn('wmx-h-5 wmx-min-w-8 wmx-w-full wmx-bg-gray-300 wmx-animate-pulse wmx-rounded-full')}
                  ></div>
                </td>
              ))}
          </tr>
        ))}
    </tbody>
  );
};
export default TableLoading;
