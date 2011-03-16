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
     * @var array Array with hourly recorded temperatures
     */
    private $_hourlyRecordedTemperature;
    /**
     * @var array Array with hourly apparent temperatures
     */
    private $_hourlyApparentTemperature;

    /**
     * @var array Array with maximum temperatures by day. For current/today requests it will only have 1 element
     */
    private $_dailyMaximumTemperature;
    /**
     * @var array Array with minimum temperatures by day. For current/today requests it will only have 1 element
     */
    private $_dailyMinimumTemperature;

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
     * @var array Array with complete weather data organized by timestamp
     */
    private $_completeWeatherData;

    /**
     * @var string The generated URL used to access the NWS service for this forecast
     */
    private $_requestURL;



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
     * @return array
     */
    public function getHourlyRecordedTemperature()
    {
        return $this->_hourlyRecordedTemperature;
    }

    /**
     * @param array $hourlyRecordedTemperature
     */
    public function setHourlyRecordedTemperature($hourlyRecordedTemperature)
    {
        $this->_hourlyRecordedTemperature = $hourlyRecordedTemperature;
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


    /**
     * @return array
     */
    public function getCompleteWeatherData()
    {
        return $this->_completeWeatherData;
    }

    /**
     * @param string $timestamp
     * @param string $weatherType
     * @param mixed $weatherData
     */
    public function setWeatherDataForTimestamp($timestamp, $weatherType, $weatherData)
    {
        $this->_completeWeatherData[$timestamp][$weatherType] = $weatherData;
    }


    /**
     * @return string
     */
    public function getRequestURL()
    {
        return $this->_requestURL;
    }

    /**
     * @param string $requestURL
     */
    public function setRequestURL($requestURL)
    {
        $this->_requestURL = $requestURL;
    }


    /**
     * Convertor from Fahrenheit to Celsius
     *  The result will be rounded to the nearest int
     *
     * @param mixed $degreesF The temperature(s) in Fahrenheit
     * @return mixed
     */
    public function convertToCelsius($degreesF)
    {
        // the input can either be a scalar temperature or an array
        if (is_array($degreesF))
        {
            $degreesC = array();

            foreach ($degreesF as $key => $value)
            {
                $degreesC[$key] = $this->convertToCelsius($value);
            }
        }
        else
        {
            $degreesC = round(($degreesF - 32) * 5/9);
        }

        return $degreesC;
    }

}
?>
