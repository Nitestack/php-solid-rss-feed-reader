import { Component, createSignal, createResource, createEffect, Show } from 'solid-js';
import axios from "axios";
import type { RSSFeed as RSSFeedType } from "./types";
import RSSFeed from "./components/RSSFeed";

const App: Component = () => {
    const [url, setUrl] = createSignal<string>();
    const [response, { refetch }] = createResource<{
        success: true,
        error: null,
        data: RSSFeedType;
    } | {
        success: false,
        error: string,
        data: null;
    }, string>(url, async (url, info) => {
        const formData = new FormData();
        formData.set("url", url);
        return axios.post("http://localhost:8080/index.php", formData).then(res => res.data);
    });
    return (
        <div class="flex flex-col items-center justify-center min-h-screen px-20">
            <div class="form-control mb-20">
                <label for="url" class="label">
                    <span class="label-text">URL</span>
                </label>
                <label class="input-group">
                    <input onInput={(ev) => setUrl(ev.currentTarget.value)} name="url" type="text" placeholder="Enter URL" class="input input-bordered" />
                    {response.loading ?
                        <button class="btn btn-square">
                            <progress class="progress w-12" />
                        </button> :
                        <button onClick={() => refetch()} class="btn btn-square"> { response()?.success ? "REL OAD" : "Add" } </button>
                    }
                </label>
            </div>
            <Show when={url()}>
                <Show when={response()?.success && !response.loading} fallback={
                    <progress class="progress w-56 bg-base-100" />
                }>
                    <RSSFeed rssFeed={response().data} />
                </Show>
            </Show>
        </div>
    );
};

export default App;