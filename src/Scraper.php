<?php
namespace Mediashare\Scraper;

use Mediashare\Scraper\Entity\Webpage;
use Mediashare\Scraper\Controller\Curl;
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
        // Curl get Webpage content
        $this->webpage = $this->curl($this->webpage);
        
        // Generate DomCrawler (Symfony Library)
        $this->dom = $this->getDomCrawler($this->webpage);
        
        // Add internal & external links from webpage with DomCrawler
        $this->webpage->addLinks($this->webpage, $this->dom);
        
        return $this;
    }

    public function curl(Webpage $webpage) {
        $curl = new Curl($webpage);
        $curl = $curl->run();
        $webpage = $curl->webpage;
        if ($curl->error) {
            $this->error = $curl->error; // Record Curl Error
        }
        return $webpage;
    }

    public function getDomCrawler(Webpage $webpage) {
        $body = $webpage->getBody()->getContent();
        $dom = new DomCrawler($body);
		return $dom;
    }
}
