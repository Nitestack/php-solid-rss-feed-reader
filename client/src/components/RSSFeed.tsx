import { createEffect, ParentComponent } from "solid-js";
import { Show } from "solid-js";
import RSSFeedItems from "./RSSFeedItems";
import RSSFeedMainCard from "./RSSFeedMainCard";
import { useAppContext } from "../AppContext";

const RSSFeed: ParentComponent = () => {
    const { url, response } = useAppContext();
    createEffect(() => {
        console.log(response());
        console.log(response.loading);
        console.log(response.error);
    });
    return (
        <Show when={url()}>
            <Show when={!response.loading && response()} fallback={<progress class="progress w-56 bg-base-100" />}>
                <Show when={response()?.success}>
                    <RSSFeedMainCard rssFeed={response().data} />
                    <RSSFeedItems items={response().data.items} />
                </Show>
                <Show when={!response()?.success}>
                    <div> {response().error} </div>
                </Show>
            </Show>
        </Show>
    );
};
export default RSSFeed;