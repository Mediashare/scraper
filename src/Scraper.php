<?php
namespace Mediashare\Scraper;

use Mediashare\Scraper\Entity\Webpage;
use Mediashare\Scraper\Controller\Guzzle;
use Symfony\Component\DomCrawler\Crawler;

Class Scraper
{
    public $webpage;
    public $dom; // DomCrawler (by Symfony)
    public $error;
    function __construct(string $url) {
        $webpage = new Webpage($url);
        $this->webpage = $webpage;
    }

    public function run() {
		// Guzzle get Webpage content
        $guzzle = new Guzzle($this->webpage);
        $guzzle = $guzzle->run();
        $this->webpage = $guzzle->webpage;
        $this->error = $guzzle->error;
        
        // Generate DomCrawler (Symfony Library)
        $body = $this->webpage->getBody()->getContent();
        $this->dom = new Crawler($body);
		return $this;
    }
}
