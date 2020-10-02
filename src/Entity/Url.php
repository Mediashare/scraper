<?php
namespace Mediashare\Scraper\Entity;

use Exception;
use Mediashare\Scraper\Entity\Webpage;

Class Url
{
    public $url;
    public $isInternal; // Check if Url is internal or external from the website.
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

    public function isInternal(Webpage $webpage): bool {
        $url = $this->url;
        // Check Url
        if (!filter_var($this->url, FILTER_VALIDATE_URL)) {
            $url = $this->reConstruct($webpage); // Add host if not url valid
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return false; // Url not valid!
            } else {
                $this->url = $url; // Url reConstructed with website host
            }
        }

        // Check if url host like webpage host.
        if ($this->getHost() == $webpage->getUrl()->getHost()):
            $this->isInternal = true;
            return true;
        else:
            $this->isInternal = false;
            return false;
        endif;

    }

    private function reConstruct(Webpage $webpage) {
        $url = $this->url;
        $website = $webpage->getUrl()->getScheme().'://'.$webpage->getUrl()->getHost();
        if ($url == "/") {
            $url = rtrim($website,"/").$url;
        } elseif ($url[0] == "#") {
            $url = rtrim($url  ,"/")."/".$url;
        } else {
            $isUrl = filter_var($url, FILTER_VALIDATE_URL);
            if (!$isUrl && ($url[0] === "/" && $url[1] !== "/")) {
                $url = rtrim($website,"/").$url;
                $isUrl = filter_var($url, FILTER_VALIDATE_URL);
            }
            if (!$isUrl && strpos($url, $website) === false) {
                $url = rtrim($website,"/")."/".$url;
                $isUrl = filter_var($url, FILTER_VALIDATE_URL);
            }
            if (!$isUrl) {
                $url = rtrim($website,"/")."/".$url;
                $isUrl = filter_var($url, FILTER_VALIDATE_URL);
            }
        }
        return $url;
    }

    public function getHost(): ?string {
        $host = parse_url($this->url, PHP_URL_HOST);
        if ($host):
            return $host;
        else:
            return false;
        endif;
    }

    public function getScheme(): ?string {
        $scheme = parse_url($this->url, PHP_URL_SCHEME);
        if ($scheme):
            return $scheme;
        else:
            return false;
        endif;
    }
}
