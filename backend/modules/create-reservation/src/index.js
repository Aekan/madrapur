import React from "react";
import ReactDOM from "react-dom";
import "./index.css";
import App from "./App";
import * as serviceWorker from "./serviceWorker";
import mockAppData from "./mockAppData";

const appData = window.appData ? window.appData : mockAppData;
const staticUrl = "aliusURL";

ReactDOM.render(<App data={appData} staticUrl={staticUrl}/>, document.getElementById('root'));

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
serviceWorker.unregister();
