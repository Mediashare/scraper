<?php
namespace Mediashare\Scraper\Controller;

use Mediashare\Kernel\Kernel;
use Mediashare\Scraper\Entity\Url;
use Mediashare\Scraper\Entity\Body;
use Mediashare\Scraper\Entity\Header;
use Mediashare\Scraper\Entity\Webpage;

class Curl
{
    public $webpage;
    public $error;
	public function __construct(Webpage $webpage) {
        $this->webpage = $webpage;
        $kernel = new Kernel();
        $this->request = $kernel->get('Curl');
   	}
   
   public function run() {
        $url = $this->webpage->getUrl();
        $response = $this->request->get($url);
        $this->webpage->getBody()->setContent($response);
		return $this;
	}
}
