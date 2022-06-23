import type { ParentComponent } from "solid-js";
import { Show } from "solid-js";
import RSSFeedItems from "./RSSFeedItems";
import RSSFeedMainCard from "./RSSFeedMainCard";
import { useAppContext } from "../AppContext";

const RSSFeed: ParentComponent = () => {
    const { url, response } = useAppContext();
    return (
        <Show when={url()}>
            <Show when={response()?.success && !response.loading} fallback={<progress class="progress w-56 bg-base-100" />}>
                <RSSFeedMainCard rssFeed={response().data} />
                <RSSFeedItems items={response().data.items} />
            </Show>
        </Show>
    );
};
export default RSSFeed;