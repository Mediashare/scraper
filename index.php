<?php
require 'vendor/autoload.php';

use Mediashare\Scraper\Scraper;
$scraper = new Scraper("http://slote.me");
$scraper->run();
dump($scraper);