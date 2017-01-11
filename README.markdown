SimpleNWS
=========

PHP library for accessing NOAA's National Weather Service.

Using the free REST API provided by the National Weather Service should be straightforward enough - except it isn't. The results are overly convoluted - made to be read by machines, not humans. The purpose of this library is to make all that easier by doing all the hard work for you - it takes a latitude and longitude and gives back a nicely formatted object with all the weather data you need.

Requires PHP 5.3 (uses namespaces). Written by Cristian Radu, <http://cristianradu.com/>. MIT licensed, see the [LICENSE](./LICENSE) file for details.

Example
-------

    // set your values for latitude and longitude
    $lat  =  40.75;
    $long = -73.92;
    
    // instantiate the library
    $simpleNWS = new SimpleNWS($lat, $long);
    
    // get the forecast (for today in this example)
    $forecast = $simpleNWS->getForecastForToday();
    
    // extract the data that you need
    $apparentTemperature = $forecast->getHourlyApparentTemperature();

Alternate use: if you prefer, you can pass the latitude and longitude parameters to the forecast function instead of the library constructor:

    $simpleNWS = new SimpleNWS();
    
    $forecast = $simpleNWS->getForecastForToday($lat, $long);

Check out **example.php** for a complete list of use cases for the library.

Library Methods
---------------

After instantiating the library with the latitude and longitude, you can use one of the following self-explanatory methods to retrieve the weather forecast:

- `getCurrentConditions()`
- `getForecastForToday()`
- `getForecastForWeek()`

Forecast Model
--------------

All the methods return a forecast model object. You can use the following methods to extract the desired weather data:

<table>
    <tr>
        <td>Weather Data</td>
        <td>Method</td>
        <td>Returns</td>
        <td>Interval</td>
        <td>Units</td>
    </tr>
    <tr>
        <td>Hourly Recorded Temperature</td>
        <td><code>getHourlyRecordedTemperature()</code></td>
        <td>array of integers</td>
        <td>hourly (every 3h)</td>
        <td>degrees F</td>
    </tr>
    <tr>
        <td>Hourly Apparent Temperature</td>
        <td><code>getHourlyApparentTemperature()</code></td>
        <td>array of integers</td>
        <td>hourly (every 3h)</td>
        <td>degrees F</td>
    </tr>
    <tr>
        <td>Daily Maximum Temperature</td>
        <td><code>getDailyMaximumTemperature()</code></td>
        <td>array of integers</td>
        <td>daily (every 24h)</td>
        <td>degrees F</td>
    </tr>
    <tr>
        <td>Daily Minimum Temperature</td>
        <td><code>getDailyMinimumTemperature()</code></td>
        <td>array of integers</td>
        <td>daily (every 24h)</td>
        <td>degrees F</td>
    </tr>
    <tr>
        <td>Hourly Precipitation</td>
        <td><code>getHourlyPrecipitation()</code></td>
        <td>array of floats</td>
        <td>hourly (every 6h)</td>
        <td>inches</td>
    </tr>
    <tr>
        <td>Hourly Snow Amount</td>
        <td><code>getHourlySnowAmount()</code></td>
        <td>array of floats</td>
        <td>hourly (every 6h)</td>
        <td>inches</td>
    </tr>
    <tr>
        <td>Hourly Wind Speed</td>
        <td><code>getHourlyWindSpeed()</code></td>
        <td>array of integers</td>
        <td>hourly (every 3h)</td>
        <td>knots</td>
    </tr>
    <tr>
        <td>Hourly Wind Direction</td>
        <td><code>getHourlyWindDirection()</code></td>
        <td>array of integers</td>
        <td>hourly (every 3h)</td>
        <td>degrees</td>
    </tr>
    <tr>
        <td>Hourly Cloud Coverage</td>
        <td><code>getHourlyCloudCover()</code></td>
        <td>array of integers</td>
        <td>hourly (every 3h)</td>
        <td>percent</td>
    </tr>
    <tr>
        <td>Hourly Humidity</td>
        <td><code>getHourlyHumidity()</code></td>
        <td>array of integers</td>
        <td>hourly (every 3h)</td>
        <td>percent</td>
    </tr>
    <tr>
        <td>Weather Conditions</td>
        <td><code>getWeatherConditions()</code></td>
        <td>array of arrays</td>
        <td>hourly (every 3h)</td>
        <td>description of weather type, intensity, coverage</td>
    </tr>
</table>

The resulted arrays will have a time layout timestamp as the key. The format is **YYYY-MM-DD-HH**. The intervals are determined by the method you used to extract the weather data (see the above table).

Example:

    Array (
        [2011-08-21-20] => 78
        [2011-08-21-23] => 74
        [2011-08-22-02] => 70
        [2011-08-22-05] => 67
        [2011-08-22-08] => 68
        [2011-08-22-11] => 75
        [2011-08-22-14] => 80
        [2011-08-22-17] => 79
    ... )

Extracting the weather conditions will return a timestamped list of arrays containing the description of the weather:

    Array (
        [2011-08-21-23] => Array (
            [weather_type] => thunderstorms
            [intensity] => heavy
            [coverage] => likely
            )
    ... )

You can convert any of the results in degrees Celsius by passing them through the convert method:

    $degreesCelsius = $forecast->convertToCelsius($forecast->getHourlyRecordedTemperature());

--------------------------------------------------------------------------------


That's it! Have fun using the library. For questions and comments drop me a line at <hi@cristianradu.com>
