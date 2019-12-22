# Scraper
:dizzy: Scrapes the information from the targeted page and provides a DomCrawler.

## Installation
```bash
composer require mediashare/scraper
```
## Usage
```php
<?php
require 'vendor/autoload.php';

use Mediashare\Scraper\Scraper;
$scraper = new Scraper("http://marquand.pro");
$scraper->run();
dump($scraper);
```