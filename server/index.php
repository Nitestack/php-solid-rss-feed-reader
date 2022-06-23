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
        public string $copyright; //Optional. Notifies about copyrighted material
        public string $description; //Required. Describes the channel
        public RSSFeedImage $image; //Optional. Allows an image to be displayed when aggregators present a feed
        public ?string $language; //Optional. Specifies the language the feed is written in
        public string $link; //Required. Defines the hyperlink to the channel
        public string $pubDate; //Optional. Defines the last publication date for the content of the feed
        public string $title; //Required. Defines the title of the channel
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
        public string $description; //Required. Describes the item
        public string $link; //Required. Defines the hyperlink to the item
        public ?string $pubDate; //Optional. Defines the last-publication date for the item
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
            $pubDate = $data->getElementsByTagName("pubDate")->item(0);
            if (isset($pubDate)) $rss_item->pubDate = $pubDate->nodeValue;
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
        $pubDate = $dom_obj->getElementsByTagName("pubDate")->item(0);
        if (isset($pubDate)) $rss_feed_object->pubDate = $pubDate->nodeValue;
        $copyright = $dom_obj->getElementsByTagName("copyright")->item(0);
        if (isset($copyright)) $rss_feed_object->copyright = $copyright->nodeValue;
        //Send response
        echo json_encode(new Response(true, null, $rss_feed_object));
    } else {
        echo json_encode(new Response(false, "Didn't found RSS feed", null));
    }
    exit;
?>