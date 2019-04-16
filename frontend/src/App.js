import React, { Component } from 'react';
import { Appbar, Container } from 'muicss/react';
import HubspotLogin from './components/SocialLogin/HubspotLogin';

class App extends Component {
  render() {
    return (
      <div>
        <Appbar/>
        <Container>
          <HubspotLogin/>
        </Container>
      </div>
    );
  }
}

export default App;
