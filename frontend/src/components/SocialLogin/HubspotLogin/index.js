import React from 'react';
import createReactClass from 'create-react-class';
import Hubspot from './lib/oauth2/components/Hubspot/Hubspot';

let HubspotLogin = createReactClass({
  getInitialState: function () {
    return {
      "data": {
        "code": "",
        "token": ""
      }
    };
  },

  hubspot: function (err, res) {
    if (!err) {
      this.setState({ data: res.profile })
    } else {
      this.setState({ data: 'something happen wrong' })
    }
  },

  render: function () {
    return <div>
      <Hubspot
        url={'http://localhost:3000'}
        clientId={'f9dbc62c-ec62-435a-b787-172c505bd7b5'}
        clientSecret={'3b0a4beb-0eb6-4f16-a879-c806996d29ec'}
        redirectUri={'http://localhost:3000'}
        scope={['contacts oauth']}
        callback={this.hubspot}
      >
        Connect Hubspot
      </Hubspot>
      <hr />
      {JSON.stringify(this.state)}
    </div>
  }
});

export default HubspotLogin;
