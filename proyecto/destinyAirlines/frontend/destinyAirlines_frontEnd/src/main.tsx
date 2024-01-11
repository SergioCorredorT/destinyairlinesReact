import React from "react"
import {App} from './components/App/App'
import './main.css'
import { createRoot } from "react-dom/client";
const root = createRoot(document.getElementById("app"));
root.render(<App />);