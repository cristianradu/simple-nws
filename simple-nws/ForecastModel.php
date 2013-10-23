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
     * @var array Array with date layouts. For breaking down by day
     */
    private $_dateLayouts;

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
    private $_rawWeatherData;

    /**
     * @var array Array with complete, ready-to-use weather data organized by day
     */
    private $_weatherData;

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
    public function getDateLayouts()
    {
        return $this->_dateLayouts;
    }

    /**
     * @param array $dateLayouts
     */
    public function setDateLayouts($dateLayouts)
    {
        $this->_dateLayouts = $dateLayouts;
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
    public function getRawWeatherData()
    {
        return $this->_rawWeatherData;
    }

    /**
     * @param string $timestamp
     * @param string $weatherType
     * @param mixed $weatherData
     */
    public function setRawWeatherDataForTimestamp($timestamp, $weatherType, $weatherData)
    {
        $this->_rawWeatherData[$timestamp][$weatherType] = $weatherData;
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
     * @return array
     */
    public function getWeatherData()
    {
        return $this->_weatherData;
    }


    /**
     * Returns the hourly sky condition based on the cloud coverage and day/night
     *
     * @return array
     */
    public function getHourlySkyCondition()
    {
        $hourlySkyCondition = array();

        $daylightHours = array_unique(array_merge(Configuration::$morningInterval, Configuration::$afternoonInterval));

        foreach ($this->_hourlyCloudCover as $key => $cloudCoverage)
        {
            $keyParts = explode('-', $key);
            $hour = $keyParts[count($keyParts)-1];
            $isDay = (in_array($hour, $daylightHours)) ? TRUE : FALSE;

            $hourlySkyCondition[$key] = $this->_getSkyCondition($cloudCoverage, $isDay);
        }

        return $hourlySkyCondition;
    }


    /**
     * Organized all raw weather data by day, in a ready-to use format
     */
    public function organizeWeatherData()
    {
        $weatherData = array();

        // loop through each of the date layouts (days)
        foreach ($this->_dateLayouts as $date)
        {
            $day = array();

            // the name of the day (in the week)
            $day['day_of_week'] = date('l', strtotime($date));

            // the maximum temperature of the day
            if (array_key_exists($date.'-08', $this->_dailyMaximumTemperature))
            {
                $day['max_temperature'] = $this->_dailyMaximumTemperature[$date.'-08'];
            }

            // the minimum temperature of the day
            if (array_key_exists($date.'-20', $this->_dailyMinimumTemperature))
            {
                $day['min_temperature'] = $this->_dailyMinimumTemperature[$date.'-20'];
            }


            // aggregate data for the morning
            $day['morning']   = $this->_aggregateWeatherData($date, Configuration::$morningInterval,   TRUE);

            // aggregate data for the afternoon
            $day['afternoon'] = $this->_aggregateWeatherData($date, Configuration::$afternoonInterval, TRUE);

            // aggregate data for the evening
            $day['evening']   = $this->_aggregateWeatherData($date, Configuration::$eveningInterval,   FALSE);

            // aggregate data for the night
            $day['night']     = $this->_aggregateWeatherData($date, Configuration::$nightInterval,     FALSE);

            // aggregate data for the whole day
            $day['full_day']  = $this->_aggregateWeatherData($date, Configuration::$fullDayInterval,   TRUE);


            $weatherData[$date] = $day;
        }
        
        $this->_weatherData = $weatherData;
    }


    /**
     * Convertor from Fahrenheit to Celsius
     * The result will be rounded to the nearest int
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


    /**
     * Function to aggregate all weather data into a time interval
     * 
     * @param string $date The date part of the key for the data array
     * @param array $timeInterval The time interval to aggregate data for
     * @param boolean $daylight Determines if the request is during the day or at night
     * @return array
     */
    private function _aggregateWeatherData($date, $timeInterval, $daylight)
    {
            $aggregateData = array();

            $aggregateData['recorded_temperature'] = $this->_averageValues($date, $timeInterval, $this->_hourlyRecordedTemperature);
            $aggregateData['apparent_temperature'] = $this->_averageValues($date, $timeInterval, $this->_hourlyApparentTemperature);
            $aggregateData['precipitation']        = $this->_averageValues($date, $timeInterval, $this->_hourlyPrecipitation);
            $aggregateData['snow_amount']          = $this->_averageValues($date, $timeInterval, $this->_hourlySnowAmount);
            $aggregateData['cloud_coverage']       = $this->_averageValues($date, $timeInterval, $this->_hourlyCloudCover);
            $aggregateData['humidity']             = $this->_averageValues($date, $timeInterval, $this->_hourlyHumidity);
            $aggregateData['weather_conditions']   = $this->_averageWeatherConditions($date, $timeInterval);
            $aggregateData['sky_condition']        = $this->_getSkyCondition($aggregateData['cloud_coverage'], $daylight);
            $aggregateData['description']          = $this->_getDescription($aggregateData['sky_condition'], $aggregateData['weather_conditions']);

            return $aggregateData;
    }


    /**
     * Calculates the average value from a list of integers
     *
     * @param string $date The date part of the key for the data array
     * @param array $hours Array with the hour interval to be used (should be defined in the configuration file)
     * @param array $dataArray The data array that contains the values
     * @return integer
     */
    private function _averageValues($date, $hours, $dataArray)
    {
        $sum   = 0;
        $count = 0;

        for ($i = 0; $i < count($hours); $i++)
        {
            $hour = $hours[$i];

            // for the night interval, the hours go into the next day, so change the date
            if (($i > 0) && ($hours[$i] < $hours[$i-1]))
            {
                $oldDateKey = array_search($date, $this->_dateLayouts);
                $newDateKey = $oldDateKey + 1;
                if (array_key_exists($newDateKey, $this->_dateLayouts))
                {
                    $date = $this->_dateLayouts[$newDateKey];
                }
            }

            $key = $date.'-'.$hour;
            if (array_key_exists($key, $dataArray))
            {
                $sum += $dataArray[$key];
            }
            $count++;
        }

        return round($sum / $count);
    }


    /**
     * Determines the average weather conditions from a list
     *
     * @param string $date The date part of the key for the weather conditions array
     * @param array $hours Array with the hour interval to be used (should be defined in the configuration file)
     * @return array
     */
    private function _averageWeatherConditions($date, $hours)
    {
        $weatherConditions = array();

        $coverageFactor = 0;
        $weatherByFactor = array();

        // loop through all the coverages and weigh them
        // definitely > likely > chance > slight chance
        // for each type of coverage, store the complete info
        foreach ($hours as $hour)
        {
            if (array_key_exists($date.'-'.$hour, $this->_weatherConditions))
            {
                $weatherInfo = $this->_weatherConditions[$date.'-'.$hour];

                switch ($weatherInfo['coverage'])
                {
                    case 'definitely':
                        $coverageFactor += 1000;
                        $weatherByFactor['definitely'] = $weatherInfo;
                        break;
                    case 'likely':
                        $coverageFactor += 100;
                        $weatherByFactor['likely'] = $weatherInfo;
                        break;
                    case 'chance':
                        $coverageFactor += 10;
                        $weatherByFactor['chance'] = $weatherInfo;
                        break;
                    case 'slight chance':
                        $coverageFactor += 1;
                        $weatherByFactor['slight-chance'] = $weatherInfo;
                        break;
                    default:
                        break;
                }
            }
        }

        // check the final weight and use the weather info from it
        switch (strlen(strval($coverageFactor)))
        {
            case 4:
                $weatherConditions = $weatherByFactor['definitely'];
                break;
            case 3:
                $weatherConditions = $weatherByFactor['likely'];
                break;
            case 2:
                $weatherConditions = $weatherByFactor['chance'];
                break;
            case 1:
                $weatherConditions =  ($coverageFactor > 0) ? $weatherByFactor['slight-chance'] : array();
                break;
            default:
                break;
        }

        return $weatherConditions;
    }


    /**
     * Describes the state of the sky based on the cloud coverage
     * Uses definitions from: http://www.weatherworks.com/files/SPECIAL_SAW_files/partly_cloudy-partly_sunny.html
     *
     * @param integer $cloudCoverage The percentage of cloud coverage
     * @param boolean $daylight Determines if the request is during the day or at night
     * @return string
     */
    private function _getSkyCondition($cloudCoverage, $daylight)
    {
        $skyCondition = '';

        switch (round($cloudCoverage / 100 * 8))
        {
            case 0:
                $skyCondition = ($daylight) ? 'Sunny' : 'Clear';
                break;
            case 1:
            case 2:
                $skyCondition = ($daylight) ? 'Mostly Sunny' : 'Mostly Clear';
                break;
            case 3:
            case 4:
            case 5:
                $skyCondition = ($daylight) ? 'Partly Sunny' : 'Partly Cloudy';
                break;
            case 6:
            case 7:
                $skyCondition = ($daylight) ? 'Mostly Cloudy' : 'Mostly Cloudy';
                break;
            case 8:
                $skyCondition = ($daylight) ? 'Cloudy' : 'Cloudy';
                break;
        }

        return $skyCondition;
    }


    /**
     * Builds an overall description of the weather for a timeframe
     *
     * @param string $skyCondition The sky condition (determined from the cloud coverage)
     * @param array $weatherConditions The weather conditions array
     * @return string
     */
    private function _getDescription($skyCondition, $weatherConditions)
    {
        if (empty($weatherConditions))
        {
            // if there are no notable weather conditions, return the sky condition
            return $skyCondition;
        }
        else
        {
            // build a descriptor for the weather conditions
            $description = '';

            switch ($weatherConditions['coverage'])
            {
                case 'chance':
                case 'slight chance':
                    $description .= $weatherConditions['coverage'].' of ';
                    break;
                case 'definitely':
                case 'likely':
                default:
                    break;
            }

            switch ($weatherConditions['intensity'])
            {
                case 'heavy':
                    $description .= $weatherConditions['intensity'].' ';
                    break;
                case 'light':
                case 'very light':
                default:
                    break;
            }

            $description .= $weatherConditions['weather_type'];

            return ucwords($description);
        }
    }
}
?>
