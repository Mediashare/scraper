<?php
namespace Mediashare\Scraper\Entity;

use Exception;
use Mediashare\Scraper\Entity\Webpage;

Class Url
{
    public $url;
    public function __construct(string $url) {
        $this->url = $url;
    }

    public function __toString() {
        return $this->url;
    }

    /**
     * Check if $this->url is url valid.
     *
     * @return boolean
     */
    public function checkUrl(): bool {
        if (filter_var($this->url, FILTER_VALIDATE_URL)) { 
            return true;
        } else {
            throw new Exception('[Url] is not valid!');
            return false;
        }
    }
}
