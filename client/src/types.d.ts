/**
 * @link https://www.w3schools.com/xml/xml_rss.asp
 */
export interface RSSFeed {
    copyright?: string; //Optional. Notifies about copyrighted material
    description: string; //Required. Describes the channel
    image?: RSSFeedImage; //Optional. Allows an image to be displayed when aggregators present a feed
    language?: string; //Optional. Specifies the language the feed is written in
    link: string; //Required. Defines the hyperlink to the channel
    title: string; //Required. Defines the title of the channel
    items: Array<RSSFeedItem>;
    pubDate?: string;
}

export interface RSSFeedItem {
    author?: string; //Optional. Specifies the e-mail address to the author of the item
    description: string; //Required. Describes the item
    link: string; //Required. Defines the hyperlink to the item
    title: string; //Required. Defines the title of the item
    pubDate?: string;
}

export interface RSSFeedImage {
    title: string;
    url: string;
    link: string;
}