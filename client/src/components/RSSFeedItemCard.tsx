import type { ParentComponent } from "solid-js";
import type { RSSFeedItem } from "../types";

const RSSFeedItemCard: ParentComponent<{ item: RSSFeedItem; }> = (props) => {
    const item = () => props.item;
    return (
        <div class="card w-96 bg-base-200 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">
                    <a target="_blank" class="link link-hover" href={item().link}>
                        <span innerHTML={item().title} />
                    </a>
                    {item().author && <div class="badge badge-secondary">{item().author}</div>}
                </h2>
                <p innerHTML={item().description} />
            </div>
            {item().pubDate &&
                <div class="card-actions justify-center">
                    <div class="badge badge-outline">{new Date(item().pubDate).toLocaleDateString(undefined, {
                        day: "2-digit",
                        weekday: "long",
                        month: "long",
                        year: "numeric",
                        hour: "2-digit",
                        minute: "2-digit"
                    })}</div>
                </div>
            }
        </div>
    );
};
export default RSSFeedItemCard;