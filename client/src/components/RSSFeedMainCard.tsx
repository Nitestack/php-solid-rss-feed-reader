import type { ParentComponent } from "solid-js";
import type { RSSFeed } from "../types";

const RSSFeedMainCard: ParentComponent<{ rssFeed: RSSFeed; }> = (props) => {
    const rssFeed = () => props.rssFeed;
    return (
        <div class="card lg:card-side w-96 lg:w-auto bg-base-200 shadow-xl">
            <figure class="px-10 pt-10 lg:p-0">
                <img class="w-[400px] h-[250px] lg:w-[400px] lg:h-[400px] rounded-xl lg:rounded-none" src={rssFeed().image.url} alt={rssFeed().image.title} />
            </figure>
            <div class="card-body lg:items-start lg:text-left items-center text-center">
                <h2 class="card-title">
                    <a target="_blank" class="link link-hover" href={rssFeed().link}>
                        <span innerHTML={rssFeed().title} />
                    </a>
                    {rssFeed().language && <div class="badge badge-secondary">{rssFeed().language}</div>}
                </h2>
                <p innerHTML={rssFeed().description} />
                {(rssFeed().pubDate || rssFeed().copyright) &&
                    <div class="card-actions justify-center lg:justify-left">
                        {rssFeed().pubDate &&
                            <div class="badge badge-outline">{new Date(rssFeed().pubDate).toLocaleDateString(undefined, {
                                day: "2-digit",
                                weekday: "long",
                                month: "long",
                                year: "numeric",
                                hour: "2-digit",
                                minute: "2-digit"
                            })}</div>
                        }
                        {rssFeed().copyright &&
                            <div class="badge badge-outline"> &copy; {rssFeed().copyright} </div>
                        }
                    </div>
                }
            </div>
        </div>
    );
};
export default RSSFeedMainCard;