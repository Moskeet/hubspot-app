import React from 'react';
import createReactClass from 'create-react-class';
import PropTypes from 'prop-types';
import keys from 'object-keys';
import './Hubspot.scss';
import {hubspotLogin} from '../../actions/oauth';
import { Button } from 'muicss/react';

let Hubspot = createReactClass({
  getDefaultProps: function () {
    return {
      url: process.env.REACT_APP_HUBSPOT_REDIRECT_URL,
      clientId: '',
      clientSecret: '',
      redirectUri: process.env.REACT_APP_HUBSPOT_REDIRECT_URL,
      authorizationUrl: process.env.REACT_APP_HUBSPOT_AUTH_URL,
      scope: [''],
      optionalScope: [''],
      width: 800,
      height: 600
    };
  },

  getAttributesForButton: function () {
    return keys(this.props).reduce((acc, prop) => {
      if (['style', 'className', 'disabled'].some(wantedProp => wantedProp === prop)) {
        acc[prop] = this.props[prop];
      }
      return acc;
    }, {});
  },

  handleClick: function () {
    hubspotLogin(this.props).then(res => {
      this.props.callback(null, res);
    }, error => {
      this.props.callback(error, null);
    });
  },

  render: function () {
    const buttonAttrs = this.getAttributesForButton();
    return <Button color="primary" {...buttonAttrs} onClick={this.handleClick}>{this.props.children} </Button>
  }
});

Hubspot.propTypes = {
  url: PropTypes.string.isRequired,
  redirectUri: PropTypes.string.isRequired,
  clientId: PropTypes.string.isRequired,
  clientSecret: PropTypes.string.isRequired,
  scope: PropTypes.array.isRequired,
  callback: PropTypes.func.isRequired
};

export default Hubspot;
