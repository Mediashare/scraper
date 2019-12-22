<?php
namespace Mediashare\Scraper\Controller;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Mediashare\Scraper\Entity\Url;
use Mediashare\Scraper\Entity\Body;
use Mediashare\Scraper\Entity\Header;
use Mediashare\Scraper\Entity\Webpage;
use GuzzleHttp\Exception\RequestException;

class Guzzle
{
    public $webpage;
    public $error;
	public function __construct(Webpage $webpage) {
		$this->webpage = $webpage;
   	}
   
   public function run() {
        $url = $this->webpage->getUrl();
        $guzzle = new Client(['http_errors' => false]);
		try {
			$guzzle = $guzzle->request('GET', (string) $url, [
				'headers' => [
					'User-Agent' => $this->getUserAgent()
				],
				'verify' => false,
				'on_stats' => function (TransferStats $stats) {
					$performances = $stats->getHandlerStats();
					// $this->webpage->getHeader()->setTransferTime($stats->getTransferTime());
					// You must check if a response was received before using the
					// response object.
					if ($stats->hasResponse()) {
						// If php-curl is not installed
						if (isset($performances["size_download"])) {$this->webpage->getHeader()->setDownloadSize($performances["size_download"]);}
						if (isset($performances["total_time"])) {$this->webpage->getHeader()->setTransferTime($performances["total_time"]);}
					}
				}
			]);
		} catch (RequestException $exception) {
			$this->error = [
				'type' => 'guzzle',
				'message' => $exception->getMessage(),
				'url' => (string) $url,
            ];
            throw new Exception("[Guzzle] " . $exception->getMessage());
			return false;
		}

		$httpCode = $guzzle->getStatusCode();
		// Error httpCode
		if ($httpCode >= 400 ) {
			$this->webpage->getHeader()->setHttpCode($httpCode);
			foreach ($guzzle->getHeaders() as $name => $values) {
				$headers[$name] = implode(', ', $values);
			}
			$this->url->setExcluded(true);
			$this->errors[] = [
				'type' => 'guzzle',
				'message' => 'Response status: '.$httpCode,
				'url' => $this->url->getUrl(),
			];
			return false;
		}
		
		$this->webpage->getHeader()->setHttpCode($httpCode);
		foreach ($guzzle->getHeaders() as $name => $values) {
			$headers[$name] = implode(', ', $values);
		}
		$this->webpage->getHeader()->setContent($headers);
		$this->webpage->getBody()->setContent($guzzle->getBody());

		return $this;
	}

	private function getUserAgent() {
		$userAgents = [
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.47 Safari/537.36',
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36',
			'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.108 Safari/537.36',
			'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.89 Safari/537.36',
			'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.91 Safari/537.36',
			'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0',
			'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.79 Safari/537.36',
			'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36'
		];
		return $userAgents[rand(0, (count($userAgents) - 1))];
	}
}
