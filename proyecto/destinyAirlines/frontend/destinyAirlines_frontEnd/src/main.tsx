import { StrictMode } from "react";
import { createRoot } from "react-dom/client";

import "../src/globalStyles/variables.css";
import "../src/globalStyles/buttons.css";
import "../src/globalStyles/details.css";
import "../src/globalStyles/formData.css";
import { App } from "./components/App/App";
import "./main.css";

const appElement = document.getElementById("app");

if (!appElement) {
  throw new Error("Element not found, id 'app'");
}
//El modo estricto hace que si se vuelve a abrir la ventana de Sign up, el captcha dé error por creer que se carga 2 captchas para un mismo contenedor
const root = createRoot(appElement);
root.render(
    <App />
);
