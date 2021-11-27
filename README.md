# Scraper
:dizzy: Scrapes the information from the targeted page and provides a DomCrawler.

### DomCrawler
Scraper use DomCrawler library. This is symfony component for DOM navigation for HTML and XML documents. You can retrieve [Documentation Here](https://symfony.com/doc/current/components/dom_crawler.html#usage).

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
