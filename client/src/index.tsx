/* @refresh reload */
import "./index.css";
import { render } from "solid-js/web";
import { AppContextProvider } from "./AppContext";
import App from "./App";

render(() => (
    <AppContextProvider>
        <App />
    </AppContextProvider>
), document.getElementById("root") as HTMLElement);