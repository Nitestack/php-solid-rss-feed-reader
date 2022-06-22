/**
 * @link https://www.w3schools.com/xml/xml_rss.asp
 */
export interface RSSFeed {
    category?: Array<string>; //Optional. Defines one or more categories for the feed
    cloud?: string; //Optional. Register processes to be notified immediately of updates of the feed
    copyright?: string; //Optional. Notifies about copyrighted material
    description: string; //Required. Describes the channel
    docs?: string; //Optional. Specifies a URL to the documentation of the format used in the feed
    generator?: string; //Optional. Specifies the program used to generate the feed
    image?: string; //Optional. Allows an image to be displayed when aggregators present a feed
    language?: string; //Optional. Specifies the language the feed is written in
    lastBuildDate?: string; //Optional. Defines the last-modified date of the content of the feed
    link: string; //Required. Defines the hyperlink to the channel
    managingEditor?: string; //Optional. Defines the e-mail address to the editor of the content of the feed
    pubDate?: string; //Optional. Defines the last publication date for the content of the feed
    rating?: string; //Optional. The PICS rating of the feed
    skipDays?: string; //Optional. Specifies the days where aggregators should skip updating the feed
    skipHours?: string; //Optional. Specifies the hours where aggregators should skip updating the feed
    textInput?: string; //Optional. Specifies a text input field that should be displayed with the feed
    title: string; //Required. Defines the title of the channel
    ttl?: string; //Optional. Specifies the number of minutes the feed can stay cached before refreshing it from the source
    webMaster?: string; //Optional. Defines the e-mail address to the webmaster of the feed
    items: Array<RSSFeedItem>;
}

export interface RSSFeedItem {
    author?: string; //Optional. Specifies the e-mail address to the author of the item
    category?: Array<string>; //Optional. Defines one or more categories the item belongs to
    comments?: string; //Optional. Allows an item to link to comments about that item
    description: string; //Required. Describes the item
    enclosure?: string; //Optional. Allows a media file to be included with the item
    guid?: string; //Optional. Defines a unique identifier for the item
    link: string; //Required. Defines the hyperlink to the item
    pubDate?: string; //Optional. Defines the last-publication date for the item
    source?: string; //Optional. Specifies a third-party source for the item
    title: string; //Required. Defines the title of the item
}