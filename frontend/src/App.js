import React, { Component } from 'react';
import { Appbar, Container } from 'muicss/react';
import HubspotLogin from './components/SocialLogin/HubspotLogin';
import axios from 'axios';
import { connect } from 'react-redux';
import { tokenChangeCodeToken } from './actions'
import ReactNotification from "react-notifications-component";
import "react-notifications-component/dist/theme.css";

const mapStateToProps = ({ token }) => {
  return {
    data: {
      code: token.code,
      token: token.token,
    },
  }
};

class App extends Component {
  constructor(props) {
    super(props);
    this.notificationDOMRef = React.createRef();
  }

  addWarning = (title, message) => {
    const { notificationDOMRef } = this;

    notificationDOMRef.current.addNotification({
      title: title,
      message: message,
      type: "success",
      insert: "top",
      container: "top-right",
      animationIn: ["animated", "fadeIn"],
      animationOut: ["animated", "fadeOut"],
      dismiss: { duration: 2000 },
      dismissable: { click: true }
    });
  };

  componentWillMount() {
    const { dispatch } = this.props;

    axios.defaults.baseURL = process.env.REACT_APP_BACKEND_API_URL;
    axios.interceptors.response.use(response => {
      return response.data;
    }, error => {
      console.log(error);
      if (!!error.response) {
        this.addWarning('XHR', error.response.data.message);
      }

      return Promise.reject(error)
    });

    axios
      .get('token/get')
      .then(response => {
        dispatch(tokenChangeCodeToken({
          code: response.code,
          token: response.token,
        }));
      })
    ;
  }

  render() {
    const { notificationDOMRef } = this;

    return (
      <div>
        <ReactNotification ref={notificationDOMRef} />
        <Appbar/>
        <Container>
          <HubspotLogin/>
        </Container>
      </div>
    );
  }
}

export default connect(mapStateToProps)(App);
