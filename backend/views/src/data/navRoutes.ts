import { __ } from '@wordpress/i18n';

type RouteKeys = '/' | 'dashboard' | 'badges' | 'customer-manager' | 'export-import' | 'analytics' | 'filters';

type Routes = Partial<{
  [key in RouteKeys]: string;
}>;

const navRoutes: Routes = {
  '/': __('Stock Manager', 'store-manager-for-woocommerce'),
  // dashboard: __('Dashboard', 'store-manager-for-woocommerce'),
  badges: __('Badge Manager', 'store-manager-for-woocommerce'),
  // 'customer-manager': __('Customer Manager', 'store-manager-for-woocommerce'),
  // 'export-import': __('Export / Import', 'store-manager-for-woocommerce'),
  // analytics: __('Analytics', 'store-manager-for-woocommerce'),
  // filters: __('Filters', 'store-manager-for-woocommerce'),
} as const;

export default navRoutes;
