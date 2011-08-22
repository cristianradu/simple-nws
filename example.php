<?php
namespace SimpleNWS;

require_once 'simple-nws/SimpleNWS.php';

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>SimpleNWS Tester</title>
        <style type="text/css">
            pre { font-family: Monaco, Consolas, monospace; font-size: 12px; }
        </style>
    </head>
    <body>
        
        <pre>

<?php

// latitude and longitude for New York City
$lat  =  40.75;
$long = -73.92;

// instantiate the library
$simpleNWS = new SimpleNWS($lat, $long);

try
{
    // request a forecast (getCurrentConditions(), getForecastForToday() or getForecastForWeek())
    $forecast = $simpleNWS->getForecastForWeek();

    // print the request URL
    $requestURL = $forecast->getRequestURL();
    $requestParts = explode('?', $requestURL);
    echo "Requested URL:\n", $requestParts[0], "?\n", $requestParts[1], "\n\n";
   
    // print the weather data
    echo 'Hourly Recorded Temperature: ',
            print_r($forecast->getHourlyRecordedTemperature(), true),"\n";
    echo 'Hourly Recorded Temperature in Celsius: ', 
            print_r($forecast->convertToCelsius($forecast->getHourlyRecordedTemperature()), true),"\n";
    echo 'Hourly Apparent Temperature: ', 
            print_r($forecast->getHourlyApparentTemperature(), true),"\n";
    echo 'Daily Maximum Temperature: ',   
            print_r($forecast->getDailyMaximumTemperature(), true),"\n";
    echo 'Daily Minimum Temperature: ',   
            print_r($forecast->getDailyMinimumTemperature(), true),"\n";
    echo 'Hourly Precipitation: ',        
            print_r($forecast->getHourlyPrecipitation(), true),"\n";
    echo 'Hourly Snow Amount: ',          
            print_r($forecast->getHourlySnowAmount(), true),"\n";
    echo 'Hourly Cloud Coverage: ',       
            print_r($forecast->getHourlyCloudCover(), true),"\n";
    echo 'Hourly Humidity: ',             
            print_r($forecast->getHourlyHumidity(), true),"\n";
    echo 'Weather Conditions: ',          
            print_r($forecast->getWeatherConditions(), true),"\n";
    echo 'Time Layouts: ',                
            print_r($forecast->getTimeLayouts(), true),"\n";
}
catch (\Exception $error)
{
    echo $error->getMessage();
}

?>
        </pre>

    </body>
</html>
