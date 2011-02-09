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
    private $latitude;
    private $longitude;
    private $timeframe;

    /**
     * @var string constant The URL for the National Weather Service interface
     */
    const NWS_URL = "http://www.weather.gov/forecasts/xml/sample_products/browser_interface/ndfdXMLclient.php?";

    /**
     * @var array The allowed values for the timeframe parameter
     */
    private $allowedTimeframeValues = array('now', 'today', 'week');


    /**
     * Class constructor
     *
     * @param float $lat Latitude
     * @param float $long Longitude
     * @param string $time Timeframe
     */
    public function __construct($lat, $long, $time)
	{
        $this->latitude  = $lat;
        $this->longitude = $long;
        $this->timeframe = $time;
        
        // validate the input parameters
        $this->_validate();

        // build the URL based on parameters
        $this->_buildURL();
    }


    /**
     * Validates the input parameters
     *
     * @return boolean
     */
    private function _validate()
    {
        // check the datatype for latitude and make sure it's in the valid range
        if ((!is_float($this->latitude)) || ($this->latitude < -90) || ($this->latitude > 90))
        {
            // invalid latitude
        }

        // check the datatype for longitude and make sure it's in the valid range
        if ((!is_float($this->longitude)) || ($this->longitude < -180) || ($this->longitude > 180))
        {
            // invalid longitude
        }

        // check timeframe
        if (!in_array($this->timeframe, $this->allowedTimeframeValues))
        {
            // invalid timeframe
        }
    }


    /**
     * Generates the NWS URL
     *
     * Example URL: http://www.weather.gov/forecasts/xml/sample_products/browser_interface/ndfdXMLclient.php
     *                  ?lat=40.75&lon=-73.92&product=time-series&begin=2011-02-09T00:00:00&end=2011-02-10T00:00:00
     *                  &maxt=maxt&mint=mint&appt=appt&wx=wx&qpf=qpf&snow=snow&sky=sky&rh=rh
     * 
     */
    private function _buildURL()
    {
        //
    }
}
?>
