import React from "react";
import { App } from "./components/App/App";
import "./main.css";
import { createRoot } from "react-dom/client";
import "../variables.css";

const appElement = document.getElementById("app");

if (appElement) {
  const root = createRoot(appElement);
  root.render(<App />);
} else {
  console.error("Element not found, id 'app'");
}
