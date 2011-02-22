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
    /**
     * @var float Latitude
     */
    private $_latitude;
    /**
     * @var float Longitude
     */
    private $_longitude;


    /**
     * Class constructor
     * Latitude and longitude values can either be stored in private variables or passed directly to the functions
     */
    public function __construct($lat = 0.0, $long = 0.0)
    {
        $this->_storeLatAndLong($lat, $long);
    }


    /**
     * Saves the supplied latitude and longitude into the private variables
     *
     * @param float $lat Latitude
     * @param float $long Longitude
     */
    private function _storeLatAndLong($lat, $long)
    {
        if (!empty($lat))
            $this->_latitude  = $lat;
        if (!empty($long))
            $this->_longitude = $long;
    }


    /**
     * Returns current weather conditions
     *
     * @param float $lat Latitude
     * @param float $long Longitude
     * @return ForecastModel
     */
    public function getCurrentConditions($lat = 0.0, $long = 0.0)
    {
        $this->_storeLatAndLong($lat, $long);

        $parser = new DWMLParser($this->_latitude, $this->_longitude, 'now');
        $forecast = $parser->getForecast();

        return $forecast;
    }

    /**
     * Returns weather forecast for the day
     *
     * @param float $lat Latitude
     * @param float $long Longitude
     * @return ForecastModel
     */
    public function getForecastForToday($lat = 0.0, $long = 0.0)
    {
        $this->_storeLatAndLong($lat, $long);

        $parser = new DWMLParser($this->_latitude, $this->_longitude, 'today');
        $forecast = $parser->getForecast();

        return $forecast;
    }

    /**
     * Returns weather forecast for the week
     *
     * @param float $lat Latitude
     * @param float $long Longitude
     * @return ForecastModel
     */
    public function getForecastForWeek($lat = 0.0, $long = 0.0)
    {
        $this->_storeLatAndLong($lat, $long);

        $parser = new DWMLParser($this->_latitude, $this->_longitude, 'week');
        $forecast = $parser->getForecast();

        return $forecast;
    }
}
?>
