import {
  ACTION_TOKEN_CHANGE_CODE,
  ACTION_TOKEN_CHANGE_TOKEN,
  ACTION_TOKEN_CHANGE_CODE_TOKEN
} from '../constants/actions.js';

export const tokenChangeCode = (code) => {
  return {
    type: ACTION_TOKEN_CHANGE_CODE,
    code,
  }
};

export const tokenChangeToken = (token) => {
  return {
    type: ACTION_TOKEN_CHANGE_TOKEN,
    token,
  }
};

export const tokenChangeCodeToken = (props) => {
  return {
    type: ACTION_TOKEN_CHANGE_CODE_TOKEN,
    ...props
  }
};
