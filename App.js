import React, { Component } from 'react';
import './App.css';

class App extends Component {
constructor(props){
	super(props);

    this.state = {
      qpai: [],
      dataRoute: 'http://localhost/panorbit/wp-json/qpai/v1/all/'
    }
}


render() {
  return (
    
    <div className="App">
      <table>
        <tr>
          <th>Id</th>
          <th>Name</th>
          <th>email</th>
        </tr>
        {this.state.qpai.map((formdata) =>
        <tr>
          <td>{formdata.id}</td>
          <td>{formdata.name}</td>
          <td>{formdata.email}</td>
        </tr>
        )}
      </table>
    </div>
  );
}

componentDidMount(){
	fetch(this.state.dataRoute)
	    .then(res => res.json())
	    .then(qpai => this.setState((prevState, props) => {
		    return { qpai: qpai.map(this.mapQpai)};
	    }));
}

mapQpai(formdata){
  return {
    id: formdata.user_id,
    name: formdata.name,
    email: formdata.email
  }
}
}


export default App;
