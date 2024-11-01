import BadgeDimension from '../CustomSettings/components/BadgeDimension';
import SelectImage from './components/SelectImage';

const ImageSettings = () => {
  return (
    <>
      <SelectImage />
      <div className='wmx-mb-4'>
        <BadgeDimension />
      </div>
    </>
  );
};
export default ImageSettings;
