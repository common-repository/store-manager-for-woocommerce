import { __ } from '@wordpress/i18n';
import { Outlet, createHashRouter } from 'react-router-dom';
import Main from '../layout/Main';
import BadgesEditor from '../pages/Badges/BadgesEditor/BadgesEditor';
import BadgesManager from '../pages/Badges/BadgesManager/BadgesManager';
import FilterEditor from '../pages/FilterEditor/FilterEditor';
import Filters from '../pages/Filters/Filters';
import StockManager from '../pages/StockManager/StockManager';

const router = createHashRouter([
  {
    path: '/',
    element: <Main />,
    children: [
      {
        path: '/',
        element: <StockManager />,
      },
      {
        path: '/badges',
        element: <Outlet />,
        children: [
          {
            index: true,
            element: <BadgesManager />,
          },
          {
            path: 'editor',
            element: <BadgesEditor />,
          },
        ],
      },
      {
        path: '/filters',
        element: <Outlet />,
        children: [
          {
            index: true,
            element: <Filters />,
          },
          {
            path: 'editor',
            element: <FilterEditor />,
          },
        ],
      },
      {
        path: '/dashboard',
        element: <div> {__('Dashboard Coming Soon', 'store-manager-for-woocommerce')}</div>,
      },

      {
        path: '/customer-manager',
        element: <div> {__('Customer Manager Coming Soon', 'store-manager-for-woocommerce')}</div>,
      },
      {
        path: '/export-import',
        element: <div> {__('Export Import Coming Soon', 'store-manager-for-woocommerce')}</div>,
      },
      {
        path: '/analytics',
        element: <div> {__('Analytics Coming Soon', 'store-manager-for-woocommerce')}</div>,
      },
    ],
  },

  {
    path: '*',
    element: <div> {__('Page Not Found', 'store-manager-for-woocommerce')}</div>,
  },
]);

export default router;
