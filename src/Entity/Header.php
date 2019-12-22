<?php
namespace Mediashare\Scraper\Entity;

class Header
{
    public $httpCode;
    public $transferTime;
    public $downloadSize;
    public $content = [];

    public function getHttpCode(): ?int
    {
        return $this->httpCode;
    }

    public function setHttpCode(int $httpCode): self
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    public function getTransferTime(): ?int
    {
        return $this->transferTime;
    }

    public function setTransferTime(?float $transferTime): self
    {
        $this->transferTime = (int) round($transferTime * 1000); // second to ms
        return $this;
    }

    public function getDownloadSize(): ?float
    {
        return $this->downloadSize;
    }

    public function setDownloadSize(?float $downloadSize): self
    {   
        $this->downloadSize = (int) round($downloadSize / 1000); // Bytes to Kb
        return $this;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): self
    {
        $this->content = $content;

        return $this;
    }
}
