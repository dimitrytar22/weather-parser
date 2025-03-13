<?php

namespace App;

class WeatherParser extends Parser
{
    private readonly string $url;
    private string $key = "3DYZ4X6QFGUJCBRMYN96CRNNN";
    private string $unitGroup = "metric";
    private string $contentType = "json";
    private string $city;

    public function __construct(string $city)
    {
        parent::__construct();
        $this->url = "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline";
        $this->city = $city;

    }

    private function addParam(&$url, array $param) : bool
    {
        if(!$this->city)
            return false;

        if(!parse_url($url, PHP_URL_QUERY))
            $url.= "?$param[key]=$param[value]";
        else
            $url.= "&$param[key]=$param[value]";
        return true;

    }

    public function setCity(string $city)
    {
        $this->city = $city;
    }

    public function execute()
    {
        $url = $this->url;

        if(!$this->city)
            return false;

        $url .= "/$this->city";
        $this->addParam($url, ['key' => 'key', 'value' => $this->key]);
        $this->addParam($url, ['key' => 'unitGroup', 'value' => $this->unitGroup]);
        $this->addParam($url, ['key' => 'contentType', 'value' => $this->contentType]);
        curl_setopt($this->ch, CURLOPT_URL, $url);
        $response = curl_exec($this->ch);
        return $response;
    }

}