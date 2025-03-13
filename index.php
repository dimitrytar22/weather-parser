<?php
require_once __DIR__.'/vendor/autoload.php';

$parser = new \App\WeatherParser("Львов");
$response = json_decode($parser->execute());
if(!$response){
    echo "Error";
}
echo "<pre>";
var_dump($response->resolvedAddress);
echo "</pre>";