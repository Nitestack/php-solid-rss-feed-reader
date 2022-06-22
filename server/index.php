<?php
    header("Access-Control-Allow-Origin: http://localhost:3000");

    class Response {
        public bool $success;
        public ?string $error;
        public ?RSSFeed $data;

        function __construct(bool $success, ?string $error, ?RSSFeed $data) {
            if (isset($error)) $this->error = $error;
            if (isset($data)) $this->data = $data;
            $this->success = $success;
        }
    }

    /**
    * @link https://www.w3schools.com/xml/xml_rss.asp
    */
    class RSSFeed {
        public $categories; //Optional. Defines one or more categories for the feed
        public $cloud; //Optional. Register processes to be notified immediately of updates of the feed
        public $copyright; //Optional. Notifies about copyrighted material
        public string $description; //Required. Describes the channel
        public $docs; //Optional. Specifies a URL to the documentation of the format used in the feed
        public $generator; //Optional. Specifies the program used to generate the feed
        public RSSFeedImage $image; //Optional. Allows an image to be displayed when aggregators present a feed
        public ?string $language; //Optional. Specifies the language the feed is written in
        public $lastBuildDate; //Optional. Defines the last-modified date of the content of the feed
        public string $link; //Required. Defines the hyperlink to the channel
        public $managingEditor; //Optional. Defines the e-mail address to the editor of the content of the feed
        public string $pubDate; //Optional. Defines the last publication date for the content of the feed
        public $rating; //Optional. The PICS rating of the feed
        public $skipDays; //Optional. Specifies the days where aggregators should skip updating the feed
        public $skipHours; //Optional. Specifies the hours where aggregators should skip updating the feed
        public RSSFeedTextInput $textInput; //Optional. Specifies a text input field that should be displayed with the feed
        public string $title; //Required. Defines the title of the channel
        public string $ttl; //Optional. Specifies the number of minutes the feed can stay cached before refreshing it from the source
        public $webMaster; //Optional. Defines the e-mail address to the webmaster of the feed
        public array $items;

        function __construct(string $description, string $link, string $title, array $items) {
            $this->description = $description;
            $this->link = $link;
            $this->title = $title;
            $this->items = $items;
        }
    }

    class RSSItem {
        public ?string $author; //Optional. Specifies the e-mail address to the author of the item
        public ?array $category; //Optional. Defines one or more categories the item belongs to
        public ?string $comments; //Optional. Allows an item to link to comments about that item
        public string $description; //Required. Describes the item
        public ?RSSFeedItemEnclosure $enclosure; //Optional. Allows a media file to be included with the item
        public ?string $guid; //Optional. Defines a unique identifier for the item
        public string $link; //Required. Defines the hyperlink to the item
        public ?string $pubDate; //Optional. Defines the last-publication date for the item
        public ?RSSFeedItemSource $source; //Optional. Specifies a third-party source for the item
        public string $title; //Required. Defines the title of the item

        function __construct($description, $link, $title) {
            $this->description = $description;
            $this->link = $link;
            $this->title = $title;
        }
    }

    class RSSFeedImage {
        public string $url; //is the URL of a GIF, JPEG or PNG image that represents the channel.
        public string $title; //describes the image, it's used in the ALT attribute of the HTML <img> tag when the channel is rendered in HTML.
        public string $link; //is the URL of the site, when the channel is rendered, the image is a link to the site. (Note, in practice the image <title> and <link> should have the same value as the channel's <title> and <link>.
    }

    class RSSFeedCloud {
        public string $domain;
        public string $port;
        public string $path;
        public string $registerProcedure;
        public string $protocol;
    }

    class RSSFeedTextInput {
        public string $title; //The label of the Submit button in the text input area.

        public string $description; //Explains the text input area.

        public string $name; //The name of the text object in the text input area.

        public string $link; //The URL of the CGI script that processes text input requests.   
    }

    class RSSFeedItemSource {
        public string $value;
        public string $url;
    }

    class RSSFeedItemEnclosure {
        public string $url;
        public string $length;
        public string $type;
    }

    class RSSFeedCategory {
        public string $value;
        public ?string $domain;
    }

    $url = $_POST["url"];
    $dom_obj = new DOMDocument();
    $dom_obj->load($url);
    if (isset($dom_obj)) {
        $content = $dom_obj->getElementsByTagName("item");
        //Add all tags to the RSSFeed object
        $title = $dom_obj->getElementsByTagName("title")->item(0)->nodeValue;
        $link = $dom_obj->getElementsByTagName("link")->item(0)->nodeValue;
        $description = $dom_obj->getElementsByTagName("description")->item(0)->nodeValue;
        //Add RSS items
        $items = array();
        foreach($content as $data) {
            //Add all tags to the RSS feed Item object
            $item_title = $data->getElementsByTagName("title")->item(0)->nodeValue;
            $item_link = $data->getElementsByTagName("link")->item(0)->nodeValue;
            $item_description = $data->getElementsByTagName("description")->item(0)->nodeValue;
            //Create the RSS feed item
            $rss_item = new RSSItem($item_description, $item_link, $item_title);
            //Additional RSS feed tags
            $author = $data->getElementsByTagName("author")->item(0);
            if (isset($author)) $rss_item->author = $author->nodeValue;
            $category = $data->getElementsByTagName("category");
            if (isset($category)) {
                $rss_item->category = array();
                foreach($category as $cat) {
                    $rss_item->category[] = $cat->nodeValue;
                }
            }
            $comments = $data->getElementsByTagName("comments")->item(0);
            if (isset($comments)) $rss_item->comments = $comments->nodeValue;
            $enclosure = $data->getElementsByTagName("enclosure")->item(0);
            if (isset($enclosure)) $rss_item->enclosure = $enclosure->nodeValue;
            $guid = $data->getElementsByTagName("guid")->item(0);
            if (isset($guid)) $rss_item->guid = $guid->nodeValue;
            $pubDate = $data->getElementsByTagName("pubDate")->item(0);
            if (isset($pubDate)) $rss_item->pubDate = $pubDate->nodeValue;
            $source = $data->getElementsByTagName("source")->item(0);
            if (isset($source)) $rss_item->source = $source->nodeValue;
            array_push($items, $rss_item);
        }
        //Create the RSS feed
        $rss_feed_object = new RSSFeed($description, $link, $title, $items);
        //Addtional RSS tags
        $imageDOM = $dom_obj->getElementsByTagName("image")->item(0);
        if (isset($imageDOM)) {
            $image = new RSSFeedImage();
            $image->url = $imageDOM->getElementsByTagName("url")->item(0)->nodeValue;
            $image->title = $imageDOM->getElementsByTagName("title")->item(0)->nodeValue;
            $image->link = $imageDOM->getElementsByTagName("link")->item(0)->nodeValue;
            $rss_feed_object->image = $image;
        }
        $language = $dom_obj->getElementsByTagName("language")->item(0);
        if (isset($language)) $rss_feed_object->language = $language->nodeValue;
        $lastBuildDate = $dom_obj->getElementsByTagName("lastBuildDate")->item(0);
        if (isset($lastBuildDate)) $rss_feed_object->lastBuildDate = $lastBuildDate->nodeValue;
        $managingEditor = $dom_obj->getElementsByTagName("managingEditor")->item(0);
        if (isset($managingEditor)) $rss_feed_object->managingEditor = $managingEditor->nodeValue;
        $pubDate = $dom_obj->getElementsByTagName("pubDate")->item(0);
        if (isset($pubDate)) $rss_feed_object->pubDate = $pubDate->nodeValue;
        $rating = $dom_obj->getElementsByTagName("rating")->item(0);
        if (isset($rating)) $rss_feed_object->rating = $rating->nodeValue;
        $skipDays = $dom_obj->getElementsByTagName("skipDays")->item(0);
        if (isset($skipDays)) $rss_feed_object->skipDays = $skipDays->nodeValue;
        $skipHours = $dom_obj->getElementsByTagName("skipHours")->item(0);
        if (isset($skipHours)) $rss_feed_object->skipHours = $skipHours->nodeValue;
        $textInputDOM = $dom_obj->getElementsByTagName("textInput")->item(0);
        if (isset($textInputDOM)) {
            $textInput = new RSSFeedTextInput();
            $textInput->title = $textInputDOM->getElementsByTagName("title")->item(0)->nodeValue;
            $textInput->description = $textInputDOM->getElementsByTagName("description")->item(0)->nodeValue;
            $textInput->name = $textInputDOM->getElementsByTagName("name")->item(0)->nodeValue;
            $textInput->link = $textInputDOM->getElementsByTagName("link")->item(0)->nodeValue;
            $rss_feed_object->textInput = $textInput;
        }
        $ttl = $dom_obj->getElementsByTagName("ttl")->item(0);
        if (isset($ttl)) $rss_feed_object->ttl = $ttl->nodeValue;
        $webMaster = $dom_obj->getElementsByTagName("webMaster")->item(0);
        if (isset($webMaster)) $rss_feed_object->webMaster = $webMaster->nodeValue;
        $category = $dom_obj->getElementsByTagName("category");
        if ($category->length >= 1) {
            $rss_feed_object->categories = array();
            foreach($category as $catDOM) {
                $cat = new RSSFeedCategory();
                $cat->value = $catDOM->nodeValue;
                $domain = $catDOM->getAttribute("domain");
                if (isset($domain)) $cat->domain = $domain;
                array_push($rss_feed_object->categories, $cat);
            }
        }
        $cloudDOM = $dom_obj->getElementsByTagName("cloud")->item(0);
        if (isset($cloudDOM)) {
            $cloud = new RSSFeedCloud();
            $cloud->domain = $cloudDOM->attributes->getNamedItem("domain")->nodeValue;
            $cloud->port = $cloudDOM->attributes->getNamedItem("port")->nodeValue;
            $cloud->path = $cloudDOM->attributes->getNamedItem("path")->nodeValue;
            $cloud->registerProcedure = $cloudDOM->attributes->getNamedItem("registerProcedure")->nodeValue;
            $cloud->protocol = $cloudDOM->attributes->getNamedItem("protocol")->nodeValue;
            $rss_feed_object->cloud = $cloud;
        }
        $copyright = $dom_obj->getElementsByTagName("copyright")->item(0);
        if (isset($copyright)) $rss_feed_object->copyright = $copyright->nodeValue;
        $docs = $dom_obj->getElementsByTagName("docs")->item(0);
        if (isset($docs)) $rss_feed_object->docs = $docs->nodeValue;
        $generator = $dom_obj->getElementsByTagName("generator")->item(0);
        if (isset($generator)) $rss_feed_object->generator = $generator->nodeValue;
        //Send response
        echo json_encode(new Response(true, null, $rss_feed_object));
    } else {
        echo json_encode(new Response(false, "Didn't found RSS feed", null));
    }
    exit;
?>