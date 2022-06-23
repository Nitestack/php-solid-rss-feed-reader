import type { Accessor, ParentComponent, Resource, Setter } from "solid-js";
import { createContext, useContext, onMount, createSignal, createResource } from "solid-js";
import axios from "axios";
import type { RSSFeed } from "./types";

type Response = {
    success: true,
    error: null,
    data: RSSFeed;
} | {
    success: false,
    error: string,
    data: null;
}

interface AppContextType {
    theme: Accessor<"light" | "dark">;
    url: Accessor<string>;
    response: Resource<Response>;
    setUrl: Setter<string>;
    toggleTheme: () => () => void;
}

const AppContext = createContext<AppContextType>({} as AppContextType);

export const AppContextProvider: ParentComponent = (props) => {
    //Page Theme Settings
    const [theme, setTheme] = createSignal<"light" | "dark">("light");
    function toggleTheme() {
        return () => {
            setTheme(currentTheme => {
                const newTheme = currentTheme == "dark" ? "light" : "dark";
                document.documentElement.setAttribute("data-theme", newTheme);
                return newTheme;
            });
        };
    };
    onMount(() => {
        setTheme(matchMedia('(prefers-color-scheme: dark)').matches ? "dark" : "light");
    });
    //Request to the PHP server
    const [url, setUrl] = createSignal<string>();
    const [response] = createResource<Response, string>(url, async (url) => {
        const formData = new FormData();
        formData.set("url", url);
        return axios.post("http://localhost:8080/index.php", formData).then(res => res.data);
    });
    return (
        <AppContext.Provider value={{
            theme,
            url,
            response,
            setUrl,
            toggleTheme
        }}>
            {props.children}
        </AppContext.Provider>
    );
};

export const useAppContext = () => useContext(AppContext);