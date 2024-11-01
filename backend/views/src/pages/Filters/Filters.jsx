import { useNavigate } from 'react-router-dom';
import Button from '../../components/Button';
import FiltersTable from './components/FiltersTable';

const Filters = () => {
  const navigate = useNavigate();
  const handleNewFilter = () => {
    navigate('editor');
  };
  return (
    <div>
      <div className='wmx-flex wmx-justify-end wmx-mb-6'>
        <Button onClick={handleNewFilter}>New Filter</Button>
      </div>
      <FiltersTable />
    </div>
  );
};
export default Filters;
