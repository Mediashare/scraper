<?php
namespace Mediashare\Scraper;

use Mediashare\Scraper\Entity\Webpage;
use Mediashare\Scraper\Controller\Guzzle;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

Class Scraper
{
    public $webpage;
    public $dom; // DomCrawler (by Symfony)
    public $error;
    function __construct(string $url) {
        $this->webpage = new Webpage($url);
    }

    public function run() {
        // Guzzle get Webpage content
        $webpage = $this->guzzle($this->webpage);
        
        // Generate DomCrawler (Symfony Library)
        $dom = $this->getDomCrawler($webpage);
        
        // Add internal & external links from webpage with DomCrawler
        $webpage->addLinks($webpage, $dom);
        
        return $this;
    }

    public function guzzle(Webpage $webpage) {
        $guzzle = new Guzzle($webpage);
        $guzzle = $guzzle->run();
        $webpage = $guzzle->webpage;
        $this->error = $guzzle->error; // Record Guzzle Error
        return $webpage;
    }

    public function getDomCrawler(Webpage $webpage) {
        $body = $webpage->getBody()->getContent();
        $dom = new DomCrawler($body);
		return $dom;
    }
}
