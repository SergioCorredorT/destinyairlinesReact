import React from "react"
import {App} from './src/App.jsx'
import './main.css'
import { createRoot } from "react-dom/client";
const root = createRoot(document.getElementById("app"));
root.render(<App />);