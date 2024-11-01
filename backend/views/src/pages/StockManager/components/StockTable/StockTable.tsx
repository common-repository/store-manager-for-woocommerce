import { useSelector } from 'react-redux';
import { toast } from 'react-toastify';
import { RootState } from '../../../../app/store';
import SingleSelect from '../../../../components/SingleSelect';
import Table from '../../../../components/Table';
import TableLoading from '../../../../components/TableLoading';
import { useGetProductsQuery, useUpdateStockActionMutation } from '../../../../features/products/productsApi';
import QuantityChange from './components/QuantityChange';
import StockToggler from './components/StockToggler';

type RequestBody = {
  backorders?: string;
  stock_status?: string;
};

const StockTable = () => {
  const { query } = useSelector((state: RootState) => state.products);
  const { data, isLoading } = useGetProductsQuery(query, {
    refetchOnMountOrArgChange: true,
  });

  const unmanagedActions = {
    instock: 'In Stock',
    outofstock: 'Out of Stock',
    onbackorder: 'On Backorder',
  };

  const managedActions = {
    no: 'Do Not Allow',
    notify: 'Allow, Notify Customer',
    yes: 'Allow',
  };

  const [updateStockAction, { isLoading: togglerStatusLoading }] = useUpdateStockActionMutation();

  const handleStockAction = async (stockStatus: string, id: number, wcStock: string | boolean) => {
    const body: RequestBody = {};

    if (wcStock) {
      body.backorders = stockStatus;
    } else {
      body.stock_status = stockStatus;
    }

    const result = await updateStockAction({
      id,
      body,
    }).unwrap();

    if (result) {
      toast.success('Stock Status Updated');
    }
  };

  return (
    <Table>
      <thead className='wmx-bg-white'>
        <tr>
          <Table.Heading className='wmx-pl-4 wmx-w-20'>Status</Table.Heading>
          <Table.Heading>ID</Table.Heading>
          <Table.Heading>Image</Table.Heading>
          <Table.Heading>Product Title</Table.Heading>
          <Table.Heading>Type</Table.Heading>
          <Table.Heading>SKU</Table.Heading>
          <Table.Heading>Regular Price</Table.Heading>
          <Table.Heading>Sale Price</Table.Heading>
          <Table.Heading>Stock Quantity</Table.Heading>
          <Table.Heading className='wmx-w-48'>Stock/Backorder</Table.Heading>
        </tr>
      </thead>
      {isLoading ? (
        <TableLoading />
      ) : (
        <tbody className='wmx-divide-y wmx-divide-gray-200 wmx-bg-white'>
          {data &&
            data.products.map((product) => (
              <>
                <tr key={product.id}>
                  <Table.Data className='wmx-pl-4 wmx-my-auto'>
                    <StockToggler product={product} checked={product.wc_stock ? true : false} />
                  </Table.Data>
                  <Table.Data>{product.id}</Table.Data>
                  <Table.Data>
                    <img src={product.image_url} alt={product.name} className='wmx-h-7 wmx-w-7' />
                  </Table.Data>
                  <Table.Data>{product.name}</Table.Data>
                  <Table.Data className='wmx-capitalize'>{product.product_type}</Table.Data>
                  <Table.Data>{product.sku}</Table.Data>
                  <Table.Data>{product.regular_price}</Table.Data>
                  <Table.Data>{product.sale_price}</Table.Data>

                  {product.wc_stock === true ? (
                    <QuantityChange id={product.id} stockQuantity={product.stock_quantity} />
                  ) : (
                    <Table.Data>{product.stock_quantity}</Table.Data>
                  )}

                  <Table.Data>
                    {product.product_type !== 'external' && (
                      <>
                        {product.wc_stock === true ? (
                          <SingleSelect
                            onChange={(changedStatus) => {
                              handleStockAction(changedStatus, product.id, product.wc_stock);
                            }}
                            selected={product.backorders}
                            items={managedActions}
                          />
                        ) : (
                          <SingleSelect
                            onChange={(changedStatus) => {
                              handleStockAction(changedStatus, product.id, product.wc_stock);
                            }}
                            selected={product.stock_status}
                            items={unmanagedActions}
                          />
                        )}
                      </>
                    )}
                  </Table.Data>
                </tr>
                {product.product_type === 'variable' &&
                  product.variations.length > 0 &&
                  product.variations.map((variation) => (
                    <tr key={variation.id}>
                      <Table.Data className='wmx-pl-4 wmx-bg-gray-100'>
                        <StockToggler product={variation} checked={variation.wc_stock === true ? true : false} />
                      </Table.Data>
                      <Table.Data className='wmx-bg-gray-100'>{variation.id}</Table.Data>
                      <Table.Data className='wmx-bg-gray-100'>
                        <img src={variation.image_url} alt={variation.name} className='wmx-h-7 wmx-w-7' />
                      </Table.Data>
                      <Table.Data className='wmx-bg-gray-100'>{variation.name}</Table.Data>
                      <Table.Data className='wmx-capitalize wmx-bg-gray-100'>{variation.product_type}</Table.Data>
                      <Table.Data className='wmx-bg-gray-100'>{variation.sku}</Table.Data>
                      <Table.Data className='wmx-bg-gray-100'>{variation.regular_price}</Table.Data>
                      <Table.Data className='wmx-bg-gray-100'>{variation.sale_price}</Table.Data>
                      {variation.wc_stock === true || variation.wc_stock === 'parent' ? (
                        <QuantityChange
                          className='wmx-bg-gray-100'
                          id={variation.id}
                          stockQuantity={variation.stock_quantity}
                        />
                      ) : (
                        <Table.Data className='wmx-bg-gray-100'>{variation.stock_quantity}</Table.Data>
                      )}
                      <Table.Data className='wmx-bg-gray-100'>
                        {variation.wc_stock !== 'parent' && (
                          <>
                            {variation.wc_stock === true ? (
                              <SingleSelect
                                onChange={(changedStatus) => {
                                  handleStockAction(changedStatus, variation.id, variation.wc_stock);
                                }}
                                selected={variation.backorders}
                                items={managedActions}
                              />
                            ) : (
                              <SingleSelect
                                onChange={(changedStatus) => {
                                  handleStockAction(changedStatus, variation.id, variation.wc_stock);
                                }}
                                selected={variation.stock_status}
                                items={unmanagedActions}
                              />
                            )}
                          </>
                        )}
                      </Table.Data>
                    </tr>
                  ))}
              </>
            ))}
        </tbody>
      )}
    </Table>
  );
};

export default StockTable;
