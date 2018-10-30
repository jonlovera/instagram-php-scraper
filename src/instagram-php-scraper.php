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
      preg_match('/window._sharedData = \{(.*?)\};/', $text, $matches);
      $json = json_decode('{' . $matches[1] . '}');

      if( !empty( $json ) ) {
        echo json_encode($json);
        die();
      }
    }
?>
