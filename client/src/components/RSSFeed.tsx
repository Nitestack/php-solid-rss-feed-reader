import { For, ParentComponent, Show } from "solid-js";
import type { RSSFeed as RSSFeedType } from "../types";

const RSSFeed: ParentComponent<{ rssFeed: RSSFeedType; }> = (props) => {
    const rssFeed = () => props.rssFeed;
    return (
        <Show when={rssFeed()}>
            <div class="card lg:card-side w-96 lg:w-auto bg-base-200 shadow-xl">
                <figure class="px-10 pt-10 lg:p-0">
                    <img class="w-[400px] h-[250px] lg:w-[400px] lg:h-[400px] rounded-xl lg:rounded-none" src={rssFeed().image.url} alt={rssFeed().image.title} />
                </figure>
                <div class="card-body lg:items-start lg:text-left items-center text-center">
                    <h2 innerHTML={rssFeed().title} class="card-title" />
                    <p innerHTML={rssFeed().description} />
                    <div class="card-actions lg:justify-end">
                        <button class="btn btn-primary">Expand items</button>
                    </div>
                </div>
            </div>
            <div></div>
        </Show>
    );
};
export default RSSFeed;