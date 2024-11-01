import { useNavigate } from 'react-router-dom';
import Input from '../../components/Input';

const FilterEditor = () => {
  const navigate = useNavigate();

  // @ts-ignore
  const handleFilterNameChange = (e) => {
    console.log(e.target.value);
  };

  return (
    <div>
      <div className='wmx-flex wmx-flex-col'>
        <label className='wmx-text-base wmx-font-semibold'>Filter Name</label>
        <Input className='wmx-w-96' placeholder='Filter Name' onChange={handleFilterNameChange} type='text' />
      </div>

      <div> </div>
    </div>
  );
};
export default FilterEditor;
