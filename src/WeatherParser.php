<?php

namespace App;

class WeatherParser extends Parser
{
    private readonly string $url;
    private string $key = "3DYZ4X6QFGUJCBRMYN96CRNNN";
    private string $unitGroup = "metric";
    private string $contentType = "json";
    private string $city;
    private string $date;

    public function __construct($city, $date = null)
    {
        parent::__construct();
        $this->url = "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline";
        $this->city = $city;
        $this->date = $date;

    }

    private function addParam(&$url, array $param): bool
    {

        if (!parse_url($url, PHP_URL_QUERY))
            $url .= "?$param[key]=$param[value]";
        else
            $url .= "&$param[key]=$param[value]";
        return true;

    }

    public function execute()
    {
        $url = $this->url;

        if (!$this->city)
            return false;

        $url .= "/$this->city";
        if ($this->date)
            $url .= "/$this->date";

        $this->addParam($url, ['key' => 'key', 'value' => $this->key]);
        $this->addParam($url, ['key' => 'unitGroup', 'value' => $this->unitGroup]);
        $this->addParam($url, ['key' => 'contentType', 'value' => $this->contentType]);
        curl_setopt($this->ch, CURLOPT_URL, $url);
        $response = curl_exec($this->ch);
        return $response;
    }

}