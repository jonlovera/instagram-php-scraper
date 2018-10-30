<?php
    /*!
     * instagram-php-scraper http://jonlov.github.io/instagram-php-scraper/
     * Scrapes an instagram user's photos, likes, videos, etc
     * Developed by Jonathan Lovera
     * MIT license
     */
     require './simple-cache.php';

    // Defining username
    $user = "zuck";

    // Defining cache folder
    $cacheFolder = 'instagram-cache';
    if (!file_exists($cacheFolder)) {
      mkdir($cacheFolder, 0777, true);
    }

    $cache = new Gilbitron\Util\SimpleCache();
    $cache->cache_path = $cacheFolder . '/';
    $cache->cache_time = 3600;

    $scraped_website = $cache->get_data("user-$user", "https://www.instagram.com/$user/");
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
