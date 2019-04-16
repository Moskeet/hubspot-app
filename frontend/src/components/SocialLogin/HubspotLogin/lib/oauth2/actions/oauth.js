import { oauth2, openPopup, pollPopup } from 'react-oauth2/src/lib/utils/helper';

// Sign in with Hubspot
export function hubspotLogin(hubspot) {
  return oauth2(hubspot)
    .then(openPopup)
    .then(pollPopup)
    .then(exchangeCodeForToken)
    .then(signIn)
    .then(closePopup);
}

function exchangeCodeForToken({ oauthData, config, window, interval, dispatch }) {
  return new Promise((resolve, reject) => {
    const data = Object.assign({}, oauthData, config);

    resolve({ window: window, interval: interval, profile: {code: data.code, token: ''} });
  });
}

function signIn({ token, user, window, interval, profile }) {
  return new Promise((resolve, reject) => {
    resolve({ window: window, interval: interval, profile });
  });

}

function closePopup({ window, interval, profile }) {
  return new Promise((resolve, reject) => {
    clearInterval(interval);
    window.close();
    resolve({ profile: profile });
  });
}
