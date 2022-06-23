import type { ParentComponent } from "solid-js";
import { createSignal, For } from "solid-js";
import type { RSSFeedItem } from "../types";
import RSSFeedItemCard from "./RSSFeedItemCard";

const RSSFeedItems: ParentComponent<{ items: Array<RSSFeedItem>; }> = (props) => {
    const items = () => props.items;
    const [page, setPage] = createSignal(1);
    const maxPages = () => Math.ceil(items().length / 12);
    function onPageInput() {
        return (ev: InputEvent & { currentTarget: HTMLInputElement; target: Element; }) => {
            const newPage = parseInt(ev.currentTarget.value);
            if (newPage && newPage <= maxPages() && newPage >= 1) setPage(newPage);
        };
    };
    function nextPage() {
        return () => {
            setPage(currentPage => {
                const newPage = currentPage + 1;
                if (newPage <= maxPages()) return newPage;
                else return currentPage;
            });
        };
    };
    function previousPage() {
        return () => {
            setPage(currentPage => {
                const newPage = currentPage - 1;
                if (newPage >= 1) return newPage;
                else return currentPage;
            });
        };
    };
    return (
        <div class="flex items-center justify-center">
            <div class="collapse">
                <input type="checkbox" />
                <div class="collapse-title text-xl flex items-center justify-center font-medium">
                    <button class="btn btn-primary"> ITEMS </button>
                </div>
                <div class="collapse-content">
                    <div class="flex items-center justify-center mb-5">
                        <div class="btn-group">
                            <button onClick={previousPage()} class="btn">«</button>
                            <button class="btn">
                                Page <input class="input ml-1 text-base-content" min={1} minLength={1} max={maxPages()} maxLength={maxPages().toString().length} type="number" value={page()} onInput={onPageInput()} />
                            </button>
                            <button onClick={nextPage()} class="btn">»</button>
                        </div>
                    </div>
                    <div class="grid xl:grid-cols-2 2xl:grid-cols-3 gap-10">
                        <For each={items().slice((page() - 1) * 12, (page() - 1) * 12 + 12)}>
                            {item => <RSSFeedItemCard item={item} />}
                        </For>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default RSSFeedItems;