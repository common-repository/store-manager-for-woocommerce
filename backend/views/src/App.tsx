import { Provider } from 'react-redux';
import { RouterProvider } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';
import store from './app/store';
import router from './routes/router';
import 'react-toastify/dist/ReactToastify.css';

const App: React.FC = () => {
	return (
		<Provider store={ store }>
			<RouterProvider router={ router } />
			<ToastContainer
				style={ {
					zIndex: 999999,
				} }
			/>
		</Provider>
	);
};
export default App;
