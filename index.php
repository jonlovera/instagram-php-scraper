<?php
    /*!
     * instagram-php-scraper http://jonlov.github.io/instagram-php-scraper/
     * Scrapes an instagram user's photos, likes, videos, etc
     * Developed by Jonathan Lovera
     * MIT license
     */
    // Defining the basic cURL function
    $user = "USERNAME_HERE";
    function curl($url) {
        $ch = curl_init();  // Initialising cURL
        curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL
        return $data;   // Returning the data from the function
    }

    $scraped_website = curl("https://www.instagram.com/$user/");

    $document = new DOMDocument();
	$document->loadHTML($scraped_website);
	$selector = new DOMXPath($document);
	$anchors = $selector->query('/html/body//script');

    header('Content-type: application/json; charset=utf-8');

    foreach($anchors as $a) {

	    $text = $a->nodeValue;
	    $text = str_replace("window._sharedData = ","",$text);

        $text = str_replace("\n","",$text);

        $text = str_replace("(window,document,'script','//connect.facebook.net/en_US/fbevents.js');","",$text);
	    $text = str_replace("!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}","",$text);

	    $text = str_replace("fbq('init', '1425767024389221');","",$text);
	    $text = str_replace("fbq('track', 'PageView');","",$text);
        $text = str_replace("window._timings.domInteractive = Date.now()","",$text);
	    $text = str_replace(";","",$text);

        $text = str_replace('&quot;', '"', $text);
        $text = utf8_encode($text);
        $text = json_decode($text);

        // switch (json_last_error()) {
        //     case JSON_ERROR_NONE:
        //         echo ' - No errors';
        //     break;
        //     case JSON_ERROR_DEPTH:
        //         echo ' - Maximum stack depth exceeded';
        //     break;
        //     case JSON_ERROR_STATE_MISMATCH:
        //         echo ' - Underflow or the modes mismatch';
        //     break;
        //     case JSON_ERROR_CTRL_CHAR:
        //         echo ' - Unexpected control character found';
        //     break;
        //     case JSON_ERROR_SYNTAX:
        //         echo ' - Syntax error, malformed JSON';
        //     break;
        //     case JSON_ERROR_UTF8:
        //         echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        //     break;
        //     default:
        //         echo ' - Unknown error';
        //     break;
        // }
        // echo $text->country_code;

        if(json_encode($text) != 'null')
            echo json_encode($text->entry_data->ProfilePage[0]->user->media->nodes);
	}
?>
