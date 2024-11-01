import { __ } from '@wordpress/i18n';
import { NavLink } from 'react-router-dom';
import navRoutes from '../../data/navRoutes';
import cn from '../../utils/cn';

const SideNav = () => {
  type RouteType = keyof typeof navRoutes;
  const routes = Object.keys(navRoutes) as RouteType[];

  return (
    <div className='wmx-bg-white wmx-flex-shrink-0 wmx-w-48'>
      <div className=' wmx-sticky wmx-top-[84px]'>
        <div className='wmx-mt-8'>
          <h3 className='wmx-flex wmx-flex-col wmx-items-center'>
            <span className='wmx-text-lg wmx-font-medium wmx-text-gray-700'>
              {__('WooCommerce', 'store-manager-for-woocommerce')}
            </span>
            <span className='wmx-text-xl wmx-font-bold wmx-text-gray-700'>
              {__('Store Manager', 'store-manager-for-woocommerce')}
            </span>
          </h3>
        </div>

        <nav className='wmx-flex wmx-flex-col wmx-mt-10'>
          {routes.map((navItem) => (
            <NavLink
              key={navItem}
              to={navItem}
              className={({ isActive }) =>
                cn(
                  'wmx-px-4 wmx-py-2 wmx-font-medium wmx-text-gray-700 focus:wmx-shadow-none !wmx-outline-none active:wmx-text-primary hover:wmx-text-primary',
                  {
                    'wmx-bg-[#F0F0F1] wmx-font-semibold !wmx-text-primary': isActive,
                  }
                )
              }
            >
              {navRoutes[navItem]}
            </NavLink>
          ))}
        </nav>
      </div>
    </div>
  );
};
export default SideNav;
