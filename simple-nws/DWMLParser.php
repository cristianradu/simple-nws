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
    private $_latitude;
    private $_longitude;
    private $_timeframe;

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
        $paramsAreValid = $this->_validate();

        // build the URL based on parameters
        $requestURL = $this->_buildURL();
        echo $requestURL,'<br><br>';

        // perform the request and get the XML data
        $xmlData = simplexml_load_file($requestURL);
        echo '<pre>',print_r($xmlData, true),'</pre>';

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
        // TODO: latitude and longitude should be in the allowed range by NWS

        // check the datatype for latitude and make sure it's in the valid range
        if ((!is_float($this->_latitude)) || ($this->_latitude < -90) || ($this->_latitude > 90))
        {
            // invalid latitude
        }

        // check the datatype for longitude and make sure it's in the valid range
        if ((!is_float($this->_longitude)) || ($this->_longitude < -180) || ($this->_longitude > 180))
        {
            // invalid longitude
        }

        // check timeframe
        if (!in_array($this->_timeframe, Configuration::$allowedTimeframeValues))
        {
            // invalid timeframe
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
        // create the forecast model object
        $this->_forecast = new ForecastModel();


        // first build the time intervals
        $timeLayout = $xmlData->data->{'time-layout'};

        // array to store the time intervals
        $timeLayouts = array();

        foreach ($timeLayout as $layout)
        {
            // the key is a unique string (i.e. "k-p24h-n7-1")
            $key = strval($layout->{'layout-key'});

            // we need to go through each value and cast it as a string, otherwise we'll end up with SimpleXML objects
            $values = array();
            foreach ($layout->{'start-valid-time'} as $time)
            {
                $values[] = strval($time);
            }

            // add the key/value pair to the temp array
            $timeLayouts[$key] = $values;
        }

        // save the intervals in the forecast model
        $this->_forecast->setTimeLayouts($timeLayouts);


        // now iterate over the parameters
        $parameters  = $xmlData->data->parameters;

        // temperature values
        foreach ($parameters->temperature as $temperature)
        {
            // current temperature
            if ($temperature->attributes()->type == 'apparent')
            {
                $this->_forecast->setCurrentTemperature(intval($temperature->value));
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
