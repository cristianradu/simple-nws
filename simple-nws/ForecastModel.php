<?php
namespace SimpleNWS;
/**
 * Forecast model class
 *
 * @author Cristian Radu <code@cristianradu.com>
 * @version 1.0
 * @package SimpleNWS
 */
class ForecastModel
{
    /**
     * @var array Array with time layouts. For breaking down by day and hour
     */
    private $_timeLayouts;

    /**
     * @var integer Current temperature. Undefined for weekly requests
     */
    private $_currentTemperature;

    /**
     * @var array Array with maximum temperatures by day. For current/today requests it will only have 1 element
     */
    private $_dailyMaximumTemperature;
    /**
     * @var array Array with minimum temperatures by day. For current/today requests it will only have 1 element
     */
    private $_dailyMinimumTemperature;

    /**
     * @var array Array with hourly recorded temperatures
     */
    private $_hourlyTemperature;
    /**
     * @var array Array with hourly apparent temperatures
     */
    private $_hourlyApparentTemperature;

    /**
     * @var array Array with hourly precipitation
     */
    private $_hourlyPrecipitation;
    /**
     * @var array Array with hourly snow amount
     */
    private $_hourlySnowAmount;
    /**
     * @var array Array with hourly cloud coverage
     */
    private $_hourlyCloudCover;
    /**
     * @var array Array with hourly humidity
     */
    private $_hourlyHumidity;

    /**
     * @var array Array with weather conditions
     */
    private $_weatherConditions;



    /**
     * @return array
     */
    public function getTimeLayouts()
    {
        return $this->_timeLayouts;
    }

    /**
     * @param array $timeLayouts
     */
    public function setTimeLayouts($timeLayouts)
    {
        $this->_timeLayouts = $timeLayouts;
    }


    /**
     * @return integer
     */
    public function getCurrentTemperature()
    {
        return $this->_currentTemperature;
    }

    /**
     * @param integer $currentTemperature
     */
    public function setCurrentTemperature($currentTemperature)
    {
        $this->_currentTemperature = $currentTemperature;
    }


    /**
     * @return array
     */
    public function getDailyMaximumTemperature()
    {
        return $this->_dailyMaximumTemperature;
    }

    /**
     * @param array $dailyMaximumTemperature
     */
    public function setDailyMaximumTemperature($dailyMaximumTemperature)
    {
        $this->_dailyMaximumTemperature = $dailyMaximumTemperature;
    }


    /**
     * @return array
     */
    public function getDailyMinimumTemperature()
    {
        return $this->_dailyMinimumTemperature;
    }

    /**
     * @param array $dailyMinimumTemperature
     */
    public function setDailyMinimumTemperature($dailyMinimumTemperature)
    {
        $this->_dailyMinimumTemperature = $dailyMinimumTemperature;
    }


    /**
     * @return array
     */
    public function getHourlyTemperature()
    {
        return $this->_hourlyTemperature;
    }

    /**
     * @param array $hourlyTemperature
     */
    public function setHourlyTemperature($hourlyTemperature)
    {
        $this->_hourlyTemperature = $hourlyTemperature;
    }


    /**
     * @return array
     */
    public function getHourlyApparentTemperature()
    {
        return $this->_hourlyApparentTemperature;
    }

    /**
     * @param array $hourlyApparentTemperature
     */
    public function setHourlyApparentTemperature($hourlyApparentTemperature)
    {
        $this->_hourlyApparentTemperature = $hourlyApparentTemperature;
    }


    /**
     * @return array
     */
    public function getHourlyPrecipitation()
    {
        return $this->_hourlyPrecipitation;
    }

    /**
     * @param array $hourlyPrecipitation
     */
    public function setHourlyPrecipitation($hourlyPrecipitation)
    {
        $this->_hourlyPrecipitation = $hourlyPrecipitation;
    }


    /**
     * @return array
     */
    public function getHourlySnowAmount()
    {
        return $this->_hourlySnowAmount;
    }

    /**
     * @param array $hourlySnowAmount
     */
    public function setHourlySnowAmount($hourlySnowAmount)
    {
        $this->_hourlySnowAmount = $hourlySnowAmount;
    }


    /**
     * @return array
     */
    public function getHourlyCloudCover()
    {
        return $this->_hourlyCloudCover;
    }

    /**
     * @param array $hourlyCloudCover
     */
    public function setHourlyCloudCover($hourlyCloudCover)
    {
        $this->_hourlyCloudCover = $hourlyCloudCover;
    }


    /**
     * @return array
     */
    public function getHourlyHumidity()
    {
        return $this->_hourlyHumidity;
    }

    /**
     * @param array $hourlyHumidity
     */
    public function setHourlyHumidity($hourlyHumidity)
    {
        $this->_hourlyHumidity = $hourlyHumidity;
    }


    /**
     * @return array
     */
    public function getWeatherConditions()
    {
        return $this->_weatherConditions;
    }

    /**
     * @param array $weatherConditions
     */
    public function setWeatherConditions($weatherConditions)
    {
        $this->_weatherConditions = $weatherConditions;
    }

}
?>
