import React, { Component } from 'react';
import Hubspot from './lib/oauth2/components/Hubspot/Hubspot';
import { connect } from 'react-redux';
import {tokenChangeCode, tokenChangeCodeToken} from '../../../actions/index';
import axios from 'axios';

const mapStateToProps = ({ token }) => {
  return {
    data: {
      code: token.code,
      token: token.token,
    },
  }
};

class HubspotLogin extends Component {
  hubspot = (err, res) => {
    const { dispatch } = this.props;


    if (!err) {
      let bodyFormData = new FormData();

      bodyFormData.set('code', res.profile.code);
      bodyFormData.set('redirectUri', process.env.REACT_APP_HUBSPOT_REDIRECT_URL);
      axios
        .post(
          'token/exchange-code',
          bodyFormData,
          { headers: {'Content-Type': 'multipart/form-data' }})
        .then(response => {
          dispatch(tokenChangeCodeToken({
            code: response.code,
            token: response.token,
          }));
        })
      ;
    }
  };

  render() {
    const { data: {token} } = this.props;

    return <div>
      {token === null ?
        <Hubspot
          url={process.env.REACT_APP_HUBSPOT_REDIRECT_URL}
          clientId={process.env.REACT_APP_HUBSPOT_CLIENT_ID}
          clientSecret=""
          redirectUri={process.env.REACT_APP_HUBSPOT_REDIRECT_URL}
          scope={['contacts oauth']}
          callback={this.hubspot}
        >
          Connect Hubspot
        </Hubspot>
        :
        JSON.stringify(this.props)
      }
    </div>
  }
}

export default connect(mapStateToProps)(HubspotLogin);
