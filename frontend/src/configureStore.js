import { createStore } from 'redux';
import createRootReducer from './reducers';

export default function configureStore() {
  return createStore(
    createRootReducer(),
    window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__()
  );
}
