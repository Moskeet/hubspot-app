import {
  ACTION_TOKEN_CHANGE_CODE,
  ACTION_TOKEN_CHANGE_TOKEN,
  ACTION_TOKEN_CHANGE_CODE_TOKEN
} from '../constants/actions.js';

const initialState = {
  code: null,
  token: null,
};

const tokenReducer = (state = initialState, action) => {
  switch (action.type) {
    case ACTION_TOKEN_CHANGE_CODE:
      return {
        ...state,
        code: action.code,
      };
    case ACTION_TOKEN_CHANGE_TOKEN:
      return {
        ...state,
        token: action.token,
      };
    case ACTION_TOKEN_CHANGE_CODE_TOKEN:
      return {
        code: action.code,
        token: action.token,
      };
    default:
      return state;
  }
};

export default tokenReducer;
