<?php
namespace SimpleNWS;
/**
 * Parser for NOAA's Digital Weather Markup Language
 *
 * Generates the NWS URL based on the input parameters, retrieves the DWML and parses it
 *
 * @author Cristian Radu <code@cristianradu.com>
 * @version 1.0
 * @package SimpleNWS
 */
class DWMLParser
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
     * @var string Timeframe. Check the configuration file for allowed values
     */
    private $_timeframe;

    /**
     * @var ForecastModel The forecast model
     */
    private $_forecast;

    
    /**
     * Class constructor
     *
     * @param float $lat Latitude
     * @param float $long Longitude
     * @param string $time Timeframe
     */
    public function __construct($lat, $long, $time)
	{
        $this->_latitude  = $lat;
        $this->_longitude = $long;
        $this->_timeframe = $time;
        
        // validate the input parameters
        $this->_validate();

        // create the forecast model object
        $this->_forecast = new ForecastModel();

        // build the URL based on parameters
        $requestURL = $this->_buildURL();
        $this->_forecast->setRequestURL($requestURL);

        // perform the request and get the XML data
        $xmlData = simplexml_load_file($requestURL);


        // check if the response is empty
        if (empty($xmlData))
        {
            throw new \Exception('Empty response from the National Weather Service. Please try again later.');
        }

        // check if the response is an error message
        if ($xmlData->h2 == 'ERROR')
        {
            throw new \Exception('Error response from the National Weather Service. Please check your input parameters and try again later.');
        }


        // parse the XML data into the forecast model
        $this->_parseXML($xmlData);
    }


    /**
     * Validates the input parameters
     *
     * @return boolean
     */
    private function _validate()
    {
        // check the datatype for latitude and make sure it's in the valid range
        if ((!is_float($this->_latitude)) || ($this->_latitude < Configuration::$minLatitude) || ($this->_latitude > Configuration::$maxLatitude))
        {
            // invalid latitude
            throw new \Exception('Invalid latitude. Allowed values are between '.Configuration::$minLatitude.' and '.Configuration::$maxLatitude);
        }

        // check the datatype for longitude and make sure it's in the valid range
        if ((!is_float($this->_longitude)) || ($this->_longitude < Configuration::$minLongitude) || ($this->_longitude > Configuration::$maxLongitude))
        {
            // invalid longitude
            throw new \Exception('Invalid longitude. Allowed values are between '.Configuration::$minLongitude.' and '.Configuration::$maxLongitude);
        }

        // check timeframe
        if (!in_array($this->_timeframe, Configuration::$allowedTimeframeValues))
        {
            // invalid timeframe
            throw new \Exception('Invalid timeframe. Allowed values: '.implode(', ', Configuration::$allowedTimeframeValues));
        }
    }


    /**
     * Generates the NWS URL
     *
     * Example URL: http://www.weather.gov/forecasts/xml/sample_products/browser_interface/ndfdXMLclient.php
     *                  ?lat=40.75&lon=-73.92&product=time-series&begin=2011-02-09T00:00:00-05:00&end=2011-02-10T00:00:00-05:00
     *                  &maxt=maxt&mint=mint&temp=temp&appt=appt&wx=wx&qpf=qpf&snow=snow&sky=sky&rh=rh
     *
     * @return string
     */
    private function _buildURL()
    {
        // start with the NWS URL
        $url  = Configuration::C_NWS_URL;

        // add the latidude and longitude
        $url .= 'lat='.$this->_latitude.'&lon='.$this->_longitude.'&';

        // add the product type
        $url .= Configuration::$productType.'&';

        // generate and add the start and end timestamps
        date_default_timezone_set('America/New_York');
        switch ($this->_timeframe)
        {
            case 'today':
                $startTimestamp = strtotime('today');
                $endTimestamp   = strtotime('tomorrow');
                break;
            case 'week':
                $startTimestamp = strtotime('today');
                $endTimestamp   = strtotime('today + 1 week');
                break;
            case 'now':
            default:
                $startTimestamp = strtotime('now');
                $endTimestamp   = strtotime('now');
                break;
        }
        $url .= 'begin='.date('c', $startTimestamp).'&end='.date('c', $endTimestamp).'&';

        // append all parameters defined in the configuration file (the format is $param=$param)
        $parameters = array();
        foreach (Configuration::$requestParameters as $param)
        {
            $parameters[] = $param.'='.$param;
        }
        $url .= implode('&', $parameters);

        return $url;
    }


    /**
     * Parses the XML data into the forecast model
     *
     * @param SimpleXMLObject $xmlData
     */
    private function _parseXML($xmlData)
    {
        // first build the time intervals
        $timeLayout = $xmlData->data->{'time-layout'};

        // arrays to store the time/date intervals
        $timeLayouts = array();
        $dateLayouts = array();

        foreach ($timeLayout as $layout)
        {
            // the key is a unique string (i.e. "k-p24h-n7-1")
            $key = strval($layout->{'layout-key'});

            // we ngo through each value and reformat it as year-month-day-hour (there are no min/sec values)
            $values = array();
            foreach ($layout->{'start-valid-time'} as $time)
            {
                $values[]      = date('Y-m-d-H', strtotime(strval($time)));
                $dateLayouts[] = date('Y-m-d',   strtotime(strval($time)));
            }

            // add the key/value pair to the temp array
            $timeLayouts[$key] = $values;
        }

        // for the days, remove the duplicates and sort
        $dateLayouts = array_unique($dateLayouts);
        sort($dateLayouts);

        // save the intervals in the forecast model
        $this->_forecast->setTimeLayouts($timeLayouts);
        $this->_forecast->setDateLayouts($dateLayouts);


        // now iterate over the parameters
        $parameters  = $xmlData->data->parameters;

        // temperature values
        foreach ($parameters->temperature as $temperature)
        {
            // hourly recorded temperatures
            if ($temperature->attributes()->type == 'hourly')
            {
                $hourlyTemperatures = array();

                // get the time layout for this parameter
                $layout = strval($temperature->attributes()->{'time-layout'});

                // we'll go through each value and assign it to its specific time interval
                for ($i = 0; $i < count($temperature->value); $i++)
                {
                    // the timestamp for this index in the time layout
                    $key = $timeLayouts[$layout][$i];
                    // the temperature value for this index
                    $value = intval($temperature->value[$i]);

                    $hourlyTemperatures[$key] = $value;

                    $this->_forecast->setRawWeatherDataForTimestamp($key, 'recorded_temperature', $value);
                }

                // save the maximum temperature in the forecast model
                $this->_forecast->setHourlyRecordedTemperature($hourlyTemperatures);
            }

            // hourly apparent temperatures
            if ($temperature->attributes()->type == 'apparent')
            {
                $apparentTemperatures = array();

                // get the time layout for this parameter
                $layout = strval($temperature->attributes()->{'time-layout'});

                // we'll go through each value and assign it to its specific time interval
                for ($i = 0; $i < count($temperature->value); $i++)
                {
                    // the timestamp for this index in the time layout
                    $key = $timeLayouts[$layout][$i];
                    // the temperature value for this index
                    $value = intval($temperature->value[$i]);

                    $apparentTemperatures[$key] = $value;

                    $this->_forecast->setRawWeatherDataForTimestamp($key, 'apparent_temperature', $value);
                }

                // save the maximum temperature in the forecast model
                $this->_forecast->setHourlyApparentTemperature($apparentTemperatures);
            }

            // daily maximum temperatures
            if ($temperature->attributes()->type == 'maximum')
            {
                $maximumTemperatures = array();

                // get the time layout for this parameter
                $layout = strval($temperature->attributes()->{'time-layout'});

                // we'll go through each value and assign it to its specific time interval
                for ($i = 0; $i < count($temperature->value); $i++)
                {
                    // the timestamp for this index in the time layout
                    $key = $timeLayouts[$layout][$i];
                    // the temperature value for this index
                    $value = intval($temperature->value[$i]);

                    $maximumTemperatures[$key] = $value;
                }

                // save the maximum temperature in the forecast model
                $this->_forecast->setDailyMaximumTemperature($maximumTemperatures);
            }

            // daily minimum temperatures
            if ($temperature->attributes()->type == 'minimum')
            {
                $minimumTemperatures = array();

                // get the time layout for this parameter
                $layout = strval($temperature->attributes()->{'time-layout'});

                // we'll go through each value and assign it to its specific time interval
                for ($i = 0; $i < count($temperature->value); $i++)
                {
                    // the timestamp for this index in the time layout
                    $key = $timeLayouts[$layout][$i];
                    // the temperature value for this index
                    $value = intval($temperature->value[$i]);

                    $minimumTemperatures[$key] = $value;
                }

                // save the minimum temperature in the forecast model
                $this->_forecast->setDailyMinimumTemperature($minimumTemperatures);
            }
        }

        // precipitation values
        foreach ($parameters->precipitation as $precipitation)
        {
            // hourly precipitation
            if ($precipitation->attributes()->type == 'liquid')
            {
                $hourlyPrecipitation = array();

                // get the time layout for this parameter
                $layout = strval($precipitation->attributes()->{'time-layout'});

                // we'll go through each value and assign it to its specific time interval
                for ($i = 0; $i < count($precipitation->value); $i++)
                {
                    // the timestamp for this index in the time layout
                    $key = $timeLayouts[$layout][$i];
                    // the temperature value for this index
                    $value = floatval($precipitation->value[$i]);

                    $hourlyPrecipitation[$key] = $value;

                    $this->_forecast->setRawWeatherDataForTimestamp($key, 'liquid_precipitation', $value);
                }

                // save the liquid precipitation in the forecast model
                $this->_forecast->setHourlyPrecipitation($hourlyPrecipitation);
            }

            // hourly snow amount
            if ($precipitation->attributes()->type == 'snow')
            {
                $hourlySnowAmount = array();

                // get the time layout for this parameter
                $layout = strval($precipitation->attributes()->{'time-layout'});

                // we'll go through each value and assign it to its specific time interval
                for ($i = 0; $i < count($precipitation->value); $i++)
                {
                    // the timestamp for this index in the time layout
                    $key = $timeLayouts[$layout][$i];
                    // the temperature value for this index
                    $value = floatval($precipitation->value[$i]);

                    $hourlySnowAmount[$key] = $value;

                    $this->_forecast->setRawWeatherDataForTimestamp($key, 'snow_amount', $value);
                }

                // save the snow amount in the forecast model
                $this->_forecast->setHourlySnowAmount($hourlySnowAmount);
            }
        }

        // hourly cloud cover
        foreach ($parameters->{'cloud-amount'} as $cloudCover)
        {
            $hourlyCloudCover = array();

            // get the time layout for this parameter
            $layout = strval($cloudCover->attributes()->{'time-layout'});

            // we'll go through each value and assign it to its specific time interval
            for ($i = 0; $i < count($cloudCover->value); $i++)
            {
                // the timestamp for this index in the time layout
                $key = $timeLayouts[$layout][$i];
                // the temperature value for this index
                $value = intval($cloudCover->value[$i]);

                $hourlyCloudCover[$key] = $value;

                $this->_forecast->setRawWeatherDataForTimestamp($key, 'cloud_cover', $value);
            }

            // save the cloud cover in the forecast model
            $this->_forecast->setHourlyCloudCover($hourlyCloudCover);
        }

        // hourly relative humidity
        foreach ($parameters->humidity as $humidity)
        {
            $hourlyHumidity = array();

            // get the time layout for this parameter
            $layout = strval($humidity->attributes()->{'time-layout'});

            // we'll go through each value and assign it to its specific time interval
            for ($i = 0; $i < count($humidity->value); $i++)
            {
                // the timestamp for this index in the time layout
                $key = $timeLayouts[$layout][$i];
                // the temperature value for this index
                $value = intval($humidity->value[$i]);

                $hourlyHumidity[$key] = $value;

                $this->_forecast->setRawWeatherDataForTimestamp($key, 'humidity', $value);
            }

            // save the relative humidity in the forecast model
            $this->_forecast->setHourlyHumidity($hourlyHumidity);
        }


        // weather conditions
        $weather = $parameters->weather;

        // get the time layout for the weather conditions
        $layout = strval($weather->attributes()->{'time-layout'});

        $hourlyWeatherConditions = array();

        // we'll go through each value and assign it to its specific time interval
        for ($i = 0; $i < count($weather->{'weather-conditions'}); $i++)
        {
            // check if we have any value for this timestamp
            if ($weather->{'weather-conditions'}[$i]->value)
            {
                // the timestamp for this index in the time layout
                $key = $timeLayouts[$layout][$i];

                // the weather conditions for this index
                $conditions = array();
                $conditions['weather_type'] = strval($weather->{'weather-conditions'}[$i]->value->attributes()->{'weather-type'});
                $conditions['intensity']    = strval($weather->{'weather-conditions'}[$i]->value->attributes()->intensity);
                $conditions['coverage']     = strval($weather->{'weather-conditions'}[$i]->value->attributes()->coverage);

                $hourlyWeatherConditions[$key] = $conditions;

                $this->_forecast->setRawWeatherDataForTimestamp($key, 'weather_conditions', $conditions);
            }
        }

        // save the weather conditions in the forecast model
        $this->_forecast->setWeatherConditions($hourlyWeatherConditions);


        // organize the raw weather data by day, in a ready-to-use format
        $this->_forecast->organizeWeatherData();
    }


    /**
     * Getter for the forecast model
     *
     * @return ForecastModel
     */
    public function getForecast()
    {
        return $this->_forecast;
    }
}
?>
