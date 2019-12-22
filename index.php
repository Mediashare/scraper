<?php
require 'vendor/autoload.php';

use Mediashare\Scraper\Scraper;
$scraper = new Scraper("http://marquand.pro");
$scraper->run();
dump($scraper);