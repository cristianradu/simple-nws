<?php
namespace SimpleNWS;

require_once 'Configuration.php';
require_once 'DWMLParser.php';
require_once 'ForecastModel.php';

/**
 * PHP library for accessing NOAA's National Weather Service
 *
 * @author Cristian Radu <code@cristianradu.com>
 * @version 1.0
 * @package SimpleNWS
 */
class SimpleNWS
{
    //

    /**
     * Returns current weather conditions
     *
     * @param float $lat Latitude
     * @param float $long Longitude
     */
    public function getCurrentConditions($lat, $long)
    {
        //
        $parser = new DWMLParser($lat, $long, 'now');
        $forecast = $parser->getForecast();
    }

    /**
     * Returns weather forecast for the day
     *
     * @param float $lat Latitude
     * @param float $long Longitude
     */
    public function getForecastForToday($lat, $long)
    {
        //
        $parser = new DWMLParser($lat, $long, 'today');
        $forecast = $parser->getForecast();
    }

    /**
     * Returns weather forecast for the week
     *
     * @param float $lat Latitude
     * @param float $long Longitude
     */
    public function getForecastForWeek($lat, $long)
    {
        //
        $parser = new DWMLParser($lat, $long, 'week');
        $forecast = $parser->getForecast();
    }
}
?>
