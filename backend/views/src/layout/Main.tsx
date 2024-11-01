import { Outlet } from 'react-router-dom';
import SideNav from './components/SideNav';

const Main = () => {
  return (
    <div className='wmx-flex wmx-mt-5 wmx-gap-6 wmx-min-h-[calc(100vh-120px)] wmx-font-sans wmx-text-base'>
      <SideNav />
      <div className='wmx-w-full wmx-mr-4'>
        <Outlet />
      </div>
    </div>
  );
};
export default Main;
