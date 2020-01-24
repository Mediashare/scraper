<?php
require 'vendor/autoload.php';

use Mediashare\Scraper\Scraper;
$scraper = new Scraper("https://mediashare.fr");
$scraper->run();
dump($scraper);