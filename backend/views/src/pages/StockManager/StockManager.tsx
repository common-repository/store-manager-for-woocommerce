import ProductFilters from './components/ProductFilters';
import StockStatus from './components/StockStatus';
import Pagination from './components/StockTable/Pagination';
import StockTable from './components/StockTable/StockTable';

const StockManager = () => {
  return (
    <>
      <StockStatus />
      <ProductFilters />
      <div className='wmx-mt-4 wmx-rounded-lg wmx-border wmx-border-gray-100 wmx-shadow wmx-overflow-hidden'>
        <StockTable />
        <Pagination />
      </div>
    </>
  );
};
export default StockManager;
