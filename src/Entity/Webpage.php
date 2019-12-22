<?php
namespace Mediashare\Scraper\Entity;

use Mediashare\Scraper\Entity\Url;
use Mediashare\Scraper\Entity\Body;
use Mediashare\Scraper\Entity\Header;
use Mediashare\Scraper\Entity\Webpage;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Webpage
{
    public $url;
    public $header;
    public $body;
    public $links = [];
    public function __construct(string $url) {
        $this->setUrl($url);
        $this->setHeader(new Header());
        $this->setBody(new Body());
    }

    public function getUrl(): ?Url
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $url = new Url($url);
        $url->checkUrl(); // Check if url is valid.
        $url->isInternal = true;
        $this->url = $url;

        return $this;
    }

    public function getHeader(): ?Header
    {
        return $this->header;
    }

    public function setHeader(?Header $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function getBody(): ?Body
    {
        return $this->body;
    }

    public function setBody(?Body $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function addLinks(Webpage $webpage, DomCrawler $dom): self {
        $urls = $dom->filter('a');
        foreach ($urls as $url) {
            $url = $url->getAttribute('href');
            if (!empty($url)):
                $link = new Url($url);
                $external = $link->isInternal($webpage);
                $this->links[] = $link;
            endif;
        }
        return $this;
    }
}
